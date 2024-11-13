<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Venta;
use App\Models\Producto;
use App\Models\Caja;
use App\Models\MetodoDePago;
use Illuminate\Support\Facades\Log;

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

    public function update(Request $request, $id) {
        
        Log::debug('Request data: ', $request->all());
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
            // Update basic fields on venta
            $venta->update([
                'monto_total' => $request->monto_total,
                'fecha_venta' => $request->fecha_venta,
            ]);
    
            // Handle updating products and quantities
            $producto_cod = $request->input('producto_cod');
            $cantidades = $request->input('cantidad');
    
            Log::debug('Producto códigos:', $producto_cod);
            Log::debug('Cantidades:', $cantidades);
            
            foreach ($producto_cod as $index => $codigo) {

                $producto = Producto::where('codigo', $codigo)->first();
                $cantidad = (float) $cantidades[$index];

                if ($producto) {
                    if ($producto->stock >= $cantidad) {
                        $producto->stock -= $cantidad;
                        $producto->save();
                        if ($venta->productos->contains($producto->codigo)) {
                            // Update the quantity if the product already exists in the sale
                            $venta->productos()->updateExistingPivot($producto->codigo, [
                                'cantidad' => $venta->productos()->where('producto_cod', $producto->codigo)->first()->pivot->cantidad + $cantidad
                            ]);
                        } else {
                            // Add the new product to the sale if it's not already in the pivot table
                            $venta->productos()->syncWithoutDetaching([
                                $producto->codigo => ['cantidad' => $cantidad]
                            ]);
                        }
                    }else {
                        // Maneja el caso en el que no hay suficiente stock
                        return back()->withErrors(['stock' => "No hay suficiente stock para el producto: {$producto->nombre}"]);
                    }
                    
                }
            }
            $venta->save();
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
