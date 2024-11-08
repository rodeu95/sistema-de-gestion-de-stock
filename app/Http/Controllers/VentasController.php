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

class VentasController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
        $this->middleware('permission:registrar-venta', ['only'=>['create','store']]);
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
            return redirect()->back()->with('error', 'No se pueden registrar ventas mientras la caja está cerrada.');
        }
        return view('ventas.create', compact('productos', 'cajaAbierta', 'metodosdepago'));

    }

    public function store(Request $request){
        // dd($request->all());
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

        $venta = Venta::create([
            'monto_total' => $validatedData['monto_total'],
            'metodo_pago_id' => $validatedData['metodo_pago_id'],
            'fecha_venta' => $validatedData['fecha_venta'] ?? now(),
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
                return back()->withErrors(['stock' => "No hay suficiente stock para el producto: {$producto->nombre}"]);
            }
        }

        $venta->save();

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Nueva venta',
            'text' => 'Venta agregada'
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

    public function export(){
        return Excel::download(new VentasExport, 'ventas.xlsx');
    }
}

