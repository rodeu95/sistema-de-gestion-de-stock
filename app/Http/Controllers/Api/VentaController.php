<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Venta;
use App\Models\Producto;
use App\Models\Caja;
use App\Models\MetodoDePago;

class VentaController extends Controller
{
    public function index()
    {
        $ventas = Venta::with('productos', 'metodoPago')->get(); // Carga las ventas con la relación 'productos'
        return response()->json(['ventas' => $ventas]);
    }

    public function edit($id)
    {
        $venta = Venta::with(['productos', 'metodoPago'])->findOrFail($id);
        $productos = Producto::all(); // Retrieve all products

        return response()->json(['venta' => $venta, 'productos' => $productos]);
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

            return response()->json([
                'success' => true,
                'message' => 'Venta agregada exitosamente',
                'producto' => $venta
            ], 201);
        }catch(\Exception $e) {
            // Respuesta JSON en caso de error
            return response()->json([
                'success' => false,
                'message' => 'Hubo un error al agregar el agregar',
                'error' => $e->getMessage()
            ], 500);
        }

    }
}
