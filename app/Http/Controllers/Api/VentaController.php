<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Venta;
use App\Models\Producto;
use App\Models\Caja;
use App\Models\MetodoDePago;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\Lote;
use Carbon\Carbon;


class VentaController extends Controller
{
    public function __construct(){
        $this->middleware('auth:sanctum');
        $this->middleware('permission:registrar-venta', ['only'=>['create','store']]);
        $this->middleware('permission:editar-venta', ['only'=>['edit','store', 'update']]);
        $this->middleware('permission:eliminar-venta', ['only' => ['destroy']]);
        $this->middleware('permission:anular-venta', ['only' => ['anularVenta']]);
    }
    public function index(Request $request)
    {
        $filter = $request->input('filter', 'all');
        Log::info('Filtro recibido:', ['filter' => $filter]);
        $ventas = Venta::query();

        switch ($filter) {
            case 'day':
                \Log::info('Aplicando filtro del día');
                $ventas->whereDate('fecha_venta', today());
                break;
            case 'week':
                \Log::info('Aplicando filtro de la semana');
                $ventas->whereBetween('fecha_venta', [now()->startOfWeek(), now()->endOfWeek()]);
                break;
            case 'month':
                \Log::info('Aplicando filtro del mes');
                $ventas->whereMonth('fecha_venta', now()->month)->whereYear('fecha_venta', now()->year);
                break;
            case 'year':
                \Log::info('Aplicando filtro del año');
                $ventas->whereYear('fecha_venta', now()->year);
                break;
            default:
                \Log::info('Mostrando todas las ventas');
                break;
        }

        $ventas = Venta::with('productos', 'metodoPago', 'vendedor')->get(); // 
        return response()->json(['ventas' => $ventas]);
    }

    public function edit($id)
    {
        $venta = Venta::with(['productos', 'metodoPago'])->findOrFail($id);
        $productos = Producto::all();
        
        $cantidad = $venta->productos->pluck('pivot.cantidad'); // Obtiene las cantidades de cada producto
        $producto_cod = $venta->productos->pluck('codigo');// Retrieve all products

        return response()->json([
            'venta' => $venta, 
            'productos' => $productos, 
            'cantidad' => $cantidad, // Devuelve la cantidad como un array
            'producto_cod' => $producto_cod
        ]);
    }

    public function create(){
        $caja = Caja::first();
        $productos = Producto::all();
        $metodosdepago = MetodoDePago::all();
        $cajaAbierta = $caja ? $caja->estado:false;
        if ($caja && !$caja->estado) {
            return redirect()->back()->with('error', 'No se pueden registrar ventas mientras la caja está cerrada.');
        }
        return view('ventas.create', compact('productos', 'cajaAbierta', 'metodosdepago'));

    }

    public function store(Request $request)
    {
        
        try {
            dd($request->all());
            $validatedData = $request->validate([
                'producto_cod' => 'required|array',
                'producto_cod.*' => 'required|exists:productos,codigo',
                'cantidad' => 'required|array|min:1',
                'cantidad.*' => 'required|numeric|min:0.01',
                'monto_total' => 'required|numeric|min:0',
                'metodo_pago_id' => 'required|exists:metodos_de_pago,id', 
                'fecha_venta' => 'nullable|date',
            ]);

            $venta = Venta::create([
                'monto_total' => $validatedData['monto_total'],
                'metodo_pago_id' => $validatedData['metodo_pago_id'],
                'fecha_venta' => $validatedData['fecha_venta'] ?? now(),
                'vendedor_id' => Auth::id(),
            ]);

            $productos = $validatedData['producto_cod'];
            $cantidades = $validatedData['cantidad'];

            foreach ($productos as $index => $producto_cod) {
                $cantidad = (float) $cantidades[$index];
                $producto = Producto::findOrFail($producto_cod);

                if ($producto->unidad == 'UN' && floor($cantidad) != $cantidad) {
                    return response()->json([
                        'success' => false,
                        'stock' => "No se puede vender fraccionado el producto: {$producto->nombre}, ya que es de tipo unidad."
                    ], 400); // Devuelve error 400 (Bad Request)
                }

                $stockRestante = $cantidad; // Cantidad total que se debe vender

                // Obtener lotes del producto ordenados por fecha de vencimiento (FIFO)
                $lotes = Lote::where('producto_id', $producto_cod)
                            ->where('cantidad', '>', 0) // Solo lotes con stock disponible
                            ->orderBy('fecha_vencimiento', 'asc') // FIFO por fecha de vencimiento
                            ->get();

                foreach ($lotes as $lote) {
                    if ($stockRestante <= 0) break; // Si ya se vendió todo, salimos del bucle

                    $cantidadLote = min($lote->cantidad, $stockRestante); // Tomar lo máximo posible del lote
                    $lote->cantidad -= $cantidadLote; // Reducir el stock del lote
                    $lote->save();

                    $stockRestante -= $cantidadLote; // Reducir la cantidad restante por vender

                    // Registrar la venta del lote en la tabla pivot
                    $venta->productos()->attach($producto_cod, [
                        'cantidad' => $cantidadLote,
                        'numero_lote' => $lote->numero_lote, // Asociar el lote utilizado
                    ]);
                }

                // Si no se pudo cubrir la cantidad requerida
                if ($stockRestante > 0) {
                    return response()->json([
                        'success' => false,
                        'stock' => "No hay suficiente stock para el producto: {$producto->nombre}"
                    ], 400); // Devuelve error 400 (Bad Request)
                }
            }

            $venta->save();

            session()->flash('swal', [
                'icon' => 'success',
                'title' => '¡Nueva Venta!',
                'text' => 'Nueva venta registrada',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Venta agregada exitosamente',
                'venta' => $venta,
                'usuario' => Auth::user()->usuario
            ], 201);
        } catch (\Exception $e) {
            // Respuesta JSON en caso de error
            return response()->json([
                'success' => false,
                'message' => 'Hubo un error al agregar la venta',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id){
        $venta = Venta::with(['productos'])->find($id);
        // $venta->created_at = Carbon::parse($venta->created_at)->format('d/m/Y H:i:s');
        return response()->json([
            'success' => true,
            'venta' => $venta
        ]);
    }


    public function update(Request $request, $id) {
        $venta = Venta::find($id);
        // Validate input data
        $request->validate([
            'producto_cod' => 'required|array',
            'producto_cod.*' => 'required|exists:productos,codigo',
            'cantidad' => 'required|array|min:1',
            'cantidad.*' => 'required|numeric|min:0.1',
            'monto_total' => 'required|numeric|min:0',
            'fecha_venta' => 'nullable|date',
        ]);
    
        if ($venta) {
            Log::debug("Venta valida", []);
            
            $producto_cod = $request->input('producto_cod');
            $cantidades = $request->input('cantidad');
    
            // Step 1: Aggregate quantities for each product code in the request
            $productosCantidad = [];
            foreach ($producto_cod as $index => $codigo) {
                $cantidad = (float) $cantidades[$index];
                if (isset($productosCantidad[$codigo])) {
                    $productosCantidad[$codigo] += $cantidad;
                } else {
                    $productosCantidad[$codigo] = $cantidad;
                }
            }
    
            foreach ($productosCantidad as $codigo => $totalCantidad) {
                $producto = Producto::where('codigo', $codigo)->first();
    
                if ($producto) {
                    if ($producto->stock >= $totalCantidad) {
                        
                        if ($venta->productos->contains($codigo)) {
                            $existingCantidad = $venta->productos()->where('producto_cod', $codigo)->first()->pivot->cantidad;
    
                            // Only update the quantity if it has changed
                            if ($existingCantidad !== $totalCantidad) {
                                // Adjust stock only for the difference in quantity
                                $difference = $totalCantidad - $existingCantidad;
                                if ($producto->stock >= $difference) {
                                    $producto->stock -= $difference;
                                    $producto->save();
    
                                    $venta->productos()->updateExistingPivot($codigo, ['cantidad' => $totalCantidad]);
                                } else {
                                    return back()->withErrors(['stock' => "No hay suficiente stock para el producto: {$producto->nombre}"]);
                                }
                            }
                        } else {
                            // Deduct stock and add new product to the sale if it's not already in the pivot table
                            $producto->stock -= $totalCantidad;
                            $producto->save();
    
                            $venta->productos()->syncWithoutDetaching([$codigo => ['cantidad' => $totalCantidad]]);
                        }
                    } else {
                        return back()->withErrors(['stock' => "No hay suficiente stock para el producto: {$producto->nombre}"]);
                    }
                }
            }
    
            // Remueve los productos que ya no están en la request
            $currentProductCodes = $venta->productos->pluck('codigo')->toArray();
            $newProductCodes = array_keys($productosCantidad);
            $removedProductCodes = array_diff($currentProductCodes, $newProductCodes);
    
            foreach ($removedProductCodes as $removedCodigo) {
                $producto = Producto::where('codigo', $removedCodigo)->first();
                if ($producto) {
                    // Revert stock for removed product
                    $removedCantidad = $venta->productos()->where('producto_cod', $removedCodigo)->first()->pivot->cantidad;
                    $producto->stock += $removedCantidad;
                    $producto->save();
    
                    // Detach the product from the sale
                    $venta->productos()->detach($removedCodigo);
                }
            }
    
            // Update basic fields on venta
            $venta->update([
                'monto_total' => $request->monto_total,
                'fecha_venta' => $request->fecha_venta,
            ]);
    
            // Flash success message for the session
            session()->flash('swal', [
                'icon' => 'success',
                'title' => 'Actualizada',
                'text' => 'Venta actualizada correctamente',
            ]);
    
            return response()->json([
                'success' => true,
                'message' => 'Venta actualizada exitosamente',
                'venta' => $venta,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Venta no encontrada',
        ], 404);
        }
    }

    public function destroy($id){

        $venta = Venta::where('id', $id)->first();

        if($venta){
            $venta->delete();
            session()->flash('swal', [
                'icon' => 'success',
                'title' => 'Eliminada',
                'text' => 'Venta eliminada correctamente'
            ]);
            return response()->json(['message' => 'Venta eliminada exitosamente']);
        }else{
            return response()->json(['message' => 'venta no encontrada'], 404);
        }
    }

    public function anularVenta($id){
        $venta = Venta::find($id);
        
        if (!$venta) {
            return response()->json(['error' => 'Venta no encontrada'], 404);
        }
        $limiteTiempo = now()->subMinutes(30);
        if ($venta->created_at < $limiteTiempo) {
            return redirect()->back()->with('error', 'Ya no es posible anular la venta.');
        }
        foreach ($venta->productos as $producto) {
            $cantidadVendida = $producto->pivot->cantidad;
    
            // Buscar el lote con la fecha de vencimiento más cercana
            $lote = $producto->lotes()->orderBy('fecha_vencimiento', 'asc')->first();
            // dd($lote);
    
            if ($lote) {
                $lote->cantidad += $cantidadVendida;
                $producto->stock += $cantidadVendida;
                $lote->save();
                $producto->save();
            } 
        }
    
        // Marcar la venta como anulada
        $venta->estado = false;
        $venta->save();
    
        return response()->json([
            'success' => true,
            'message' => 'Venta anulada exitosamente'
        ]);
    }
}
