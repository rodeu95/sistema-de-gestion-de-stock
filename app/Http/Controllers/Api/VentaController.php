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

class VentaController extends Controller
{
    public function __construct(){
        $this->middleware('auth:sanctum');
        $this->middleware('permission:registrar-venta', ['only'=>['create','store']]);
        $this->middleware('permission:editar-venta', ['only'=>['edit','store', 'update']]);
        $this->middleware('permission:eliminar-venta', ['only' => ['destroy']]);
    }
    public function index()
    {
        // $token = Auth::user()->tokens->first();
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
            'producto_cod' => $producto_cod]);
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

    public function store(Request $request){

        try{
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
                
                if ($producto->stock >= $cantidad) {
                    // Disminuye el stock total del producto
                    $producto->stock -= $cantidad;
                    $producto->save();
            
                    // Asocia el producto a la venta con la cantidad vendida
                    $venta->productos()->attach($producto_cod, ['cantidad' => $cantidad]);
                } else {
                    // Maneja el caso en el que no hay suficiente stock
                    return response()->json([
                        'success' => false,
                        'stock' => "No hay suficiente stock para el producto: {$producto->nombre}"
                    ]);
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
        }catch(\Exception $e) {
            // Respuesta JSON en caso de error
            return response()->json([
                'success' => false,
                'message' => 'Hubo un error al agregar la venta',
                'error' => $e->getMessage()
            ], 500);
        }

    }

    public function update(Request $request, $id) {
        $venta = Venta::find($id);
    
        // Validate input data
        $request->validate([
            'producto_cod' => 'required|array',
            'producto_cod.*' => 'required|exists:productos,codigo',
            'cantidad' => 'required|array|min:1',
            'cantidad.*' => 'required|numeric|min:0.01',
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
    
            // Step 2: Update or add each product in the sale based on the request data
            foreach ($productosCantidad as $codigo => $totalCantidad) {
                $producto = Producto::where('codigo', $codigo)->first();
    
                if ($producto) {
                    if ($producto->stock >= $totalCantidad) {
                        // Check if the product already exists in the sale
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
    
            // Step 3: Remove products that are no longer in the request
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
}
