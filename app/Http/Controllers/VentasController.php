<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Venta;
use App\Models\Caja;
use App\Models\MetodoDePago;
use App\Models\Producto;

class VentasController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
        $this->middleware('permission:registrar-venta', ['only'=>['create','store']]);
    }

    public function index(){
        return view('ventas.index');
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
        dd($request->all());
        $caja = Caja::first();

    // Validar si la caja está cerrada
        if (!$caja || !$caja->estado) {
            return redirect()->route('inicio')->with('error', 'No se pueden registrar ventas mientras la caja está cerrada.');
        }

        $validatedData = $request->validate([
            'producto_id' => 'required|array',
            'producto_id.*' => 'exists:productos,id',
            'cantidad' => 'required|array',
            'cantidad.*' => 'integer|min:1',
            'monto_total' => 'required|numeric|min:0',
            'metodo_pago' => 'required|exists:metodos_de_pago,id', // si tu tabla de métodos de pago se llama 'metodos_de_pago'
            'fecha_venta' => 'nullable|date',
        ]);

        $venta = Venta::create([
            'monto_total' => $validatedData['monto_total'],
            'metodo_pago' => $validatedData['metodo_pago'],
            'fecha_venta' => $validatedData['fecha_venta'] ?? now(),
        ]);

        $productos = $request->input('producto_id');
        $cantidades = $request->input('cantidad');

        foreach ($productos as $index => $producto_id) {
            $venta->productos()->attach($producto_id, ['cantidad' => $cantidades[$index]]);
        }

        $venta->save();
        return response()->json([
            'message' => 'Venta creada con éxito',
            'venta' => $venta,
        ], 201);

    }

    public function show(Venta $venta){

    }

    public function edit(Venta $venta){

    }

    public function update(Request $request){

    }

    public function destroy(Venta $venta){
        $venta->delete();
        return redirect()->route('ventas.index')
                ->withSuccess('La venta fue eliminada exitosamente.');
    }
}

