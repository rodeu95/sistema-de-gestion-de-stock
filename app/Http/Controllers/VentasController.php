<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Venta;
use App\Models\Caja;
use App\Models\MetodoDePago;
use App\Models\Producto;
use Illuminate\Support\Carbon;
use App\Exports\VentasExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use App\Models\Lote;

class VentasController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
        $this->middleware('permission:registrar-venta', ['only'=>['create','store']]);
        $this->middleware('permission:editar-venta', ['only'=>['edit','store', 'update']]);
        $this->middleware('permission:eliminar-venta', ['only' => ['destroy']]);
        $this->middleware('permission:exportar-archivos', ['only' => ['export']]);

    }

    public function index(Venta $venta){
        $caja = Caja::find(1);
        $cajaAbierta = $caja ? $caja->estado:false;

    //     $fecha = $request->input('fecha_venta')? Carbon::parse($request->input('fecha_venta'))->format('Y-m-d') : null;

    // // Si hay una fecha, filtrar las ventas por esa fecha, sino obtener todas las ventas
    //     $ventas = Venta::when($fecha, function ($query) use ($fecha) {
    //         $query->whereDate('fecha_venta', $fecha);
    //     })->with('productos', 'metodopago')->get();

        return view('ventas.index', [
            'venta' => $venta,
            'cajaAbierta' => $cajaAbierta,
            // 'fecha' => $fecha
        ]);
    }

    public function create(){
        $caja = Caja::first();
        $productos = Producto::all();
        $metodosdepago = MetodoDePago::all();
        $cajaAbierta = $caja ? $caja->estado:false;
        if ($caja && !$caja->estado) {
            return view('ventas.create', compact('productos', 'cajaAbierta', 'metodosdepago'))->with('error', 'No se pueden registrar ventas mientras la caja está cerrada.');
        }
        return view('ventas.create', compact('productos', 'cajaAbierta', 'metodosdepago'));

    }

    public function store(Request $request) {
        $caja = Caja::first();
    
        // Validar si la caja está cerrada
        if (!$caja || !$caja->estado) {
            return redirect()->route('inicio')->with('error', 'No se pueden registrar ventas mientras la caja está cerrada.');
        }
    
        $validatedData = $request->validate([
            'producto_cod' => 'required|array',
            'producto_cod.*' => 'required|exists:productos,codigo',
            'cantidad' => 'required|array|min:1',
            'cantidad.*' => 'required|numeric|min:0.01',
            'monto_total' => 'required|numeric|min:0',
            'metodo_pago_id' => 'required|exists:metodos_de_pago,id', 
            'fecha_venta' => 'nullable|date',
        ]);
    
        $productos = $validatedData['producto_cod'];
        $cantidades = $validatedData['cantidad'];
    
        // Validar stock de todos los productos antes de crear la venta
        foreach ($productos as $index => $producto_cod) {
            $cantidad = (float) $cantidades[$index];
            $producto = Producto::findOrFail($producto_cod);
    
            // Validar si el producto no puede ser fraccionado
            if ($producto->unidad == 'UN' && floor($cantidad) != $cantidad) {
                return response()->json([
                    'success' => false,
                    'stock' => "No se puede vender fraccionado el producto: {$producto->nombre}, ya que es de tipo unidad."
                ], 400);
            }
    
            // Verificar stock total del producto
            if ($producto->stock < $cantidad) {
                return response()->json([
                    'success' => false,
                    'stock' => "Stock insuficiente para el producto: {$producto->nombre}."
                ], 400);
            }
        }
    
        // Crear la venta solo después de todas las validaciones
        $venta = Venta::create([
            'monto_total' => $validatedData['monto_total'],
            'metodo_pago_id' => $validatedData['metodo_pago_id'],
            'fecha_venta' => $validatedData['fecha_venta'] ?? now(),
            'vendedor_id' => Auth::id(),
        ]);
    
        // Procesar la reducción de stock y registrar la venta
        foreach ($productos as $index => $producto_cod) {
            $cantidad = (float) $cantidades[$index];
            $producto = Producto::findOrFail($producto_cod);
            $stockRestante = $cantidad; // Cantidad total que se debe vender
    
            // Obtener lotes del producto ordenados por fecha de vencimiento (FIFO)
            $lotes = Lote::where('producto_cod', $producto_cod)
                        ->where('cantidad', '>', 0) // Solo lotes con stock disponible
                        ->orderBy('fecha_vencimiento', 'asc') // FIFO por fecha de vencimiento
                        ->get();
    
            foreach ($lotes as $lote) {
                if ($stockRestante <= 0) break;
    
                $cantidadLote = min($lote->cantidad, $stockRestante); // Tomar lo máximo posible del lote
                $lote->cantidad -= $cantidadLote; // Reducir el stock del lote
                $lote->save();
    
                $stockRestante -= $cantidadLote;
    
                // Registrar la venta del lote en la tabla pivot
                $venta->productos()->attach($producto_cod, [
                    'cantidad' => $cantidadLote,
                ]);
            }
    
            // Reducir el stock total del producto
            $producto->stock -= $cantidad;
            $producto->save();
        }
    
        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Nueva venta',
            'text' => 'Venta agregada',
            'confirmButtonColor' => "#aed5b6",
        ]);
    
        return redirect()->route('ventas.create');
    }
    

    public function show(Venta $venta){
        $caja = Caja::find(1);
        $cajaAbierta = $caja ? $caja->estado:false;

        return view('ventas.show', [
            'venta' => $venta,
            'cajaAbierta' => $cajaAbierta
        ]);
    }

    public function edit(Venta $venta){
        $caja = Caja::find(1);
        $cajaAbierta = $caja ? $caja->estado:false;
        $productos = Producto::all();

        return view('ventas.edit', [
            'venta' => $venta,
            'cajaAbierta' => $cajaAbierta,
            'productos' => $productos
        ]);
    }

    public function update(Request $request, Venta $venta){
        $venta->update($request->all());
        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Actualizado',
            'text' => 'Venta actualizado correctamente'
        ]);
        return redirect()->back();
                // ->withSuccess('Venta actualizada.');
    }

    public function destroy(Venta $venta){
        $venta->delete();
        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Eliminado',
            'text' => 'Venta eliminada correctamente'
        ]);
        return redirect()->route('ventas.index');
                // ->withSuccess('La venta fue eliminada exitosamente.');
    }


    public function export(Request $request)
    {
    $fecha = $request->input('fecha_venta');
    $año = $request->input('year');
    $mes = $request->input('month');
    $fechaIni = $request->input('fechaIni');
    $fechaFin = $request->input('fechaFin');

    $ventas = Venta::query();

    if ($fecha) {
        $ventas->whereDate('fecha_venta', $fecha);
    }

    if ($año) {
        $ventas->whereYear('fecha_venta', $año);
    }

    if ($mes) {
        $ventas->whereMonth('fecha_venta', $mes);
    }

    if($fechaIni && $fechaFin){
        $ventas->whereBetween('fecha_venta', [$fechaIni, $fechaFin]);
    }

    $ventas = $ventas->get();

    return Excel::download(new VentasExport($ventas), 'ventas.xlsx');
    }

}

