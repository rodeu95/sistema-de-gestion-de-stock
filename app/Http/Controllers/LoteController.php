<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lote;
use App\Models\Producto;
use App\Models\Caja;

class LoteController extends Controller
{
    public function index(){
       
        $lotes = Lote::with('producto') 
        ->where('cantidad', '>', 0)
        ->orderBy('fecha_ingreso', 'desc') 
        ->get();

        $caja = Caja::find(1);
        $cajaAbierta = $caja ? $caja->estado : false;
        
        return view('lotes.index', compact('lotes', 'cajaAbierta'));
    }

    public function store(Request $request)
    {

        // dd($request->all());
        $request->validate([
            'producto_cod' => 'required|exists:productos,codigo',
            'numero_lote' => 'required',
            'cantidad' => 'required|numeric|min:1',
            'fecha_ingreso' => 'nullable|date',
            'fecha_vencimiento' => 'required|date|after_or_equal:fecha_ingreso',
        ]);

        $producto = Producto::where('codigo', $request->producto_cod)->first();

        if ($producto) {
            $producto->stock += $request->cantidad; // Sumar la cantidad del lote al stock
            $producto->save();

            if ($producto->unidad === 'UN' && $request->cantidad < 1) {
                return redirect()->back()->withErrors([
                    'cantidad' => 'La cantidad no puede ser menor a 1 para productos con unidad "UN".'
                ])->withInput();
            }
        }

        $lote = new Lote();
        $lote->producto_cod = $request->producto_cod;
        $lote->numero_lote = $request->numero_lote;
        $lote->cantidad = $request->cantidad;
        $lote->fecha_ingreso = $request->fecha_ingreso;
        $lote->fecha_vencimiento = $request->fecha_vencimiento;
        $lote->save();

        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Lote agregado!',
            'text' => 'El lote se ha agregado correctamente'
        ]);

        // Redirigir con mensaje de éxito
        return redirect()->back()->with('success', 'Lote agregado exitosamente.');
    }

    public function destroy($numero_lote, $producto_cod)
    {
        try{
            $producto = Producto::where('codigo', $producto_cod)->first();
            $lote = Lote::query()
                        ->where('numero_lote', $numero_lote)
                        ->where('producto_cod', $producto_cod)
                        ->first();
            // $lote = Lote::find(['numero_lote' => $numero_lote, 'producto_cod' => $producto_cod]);

            \Log::debug($lote);
            if (!$lote) {
                \Log::warning("Lote no encontrado para numero_lote: $numero_lote y producto_cod: $producto_cod");
                session()->flash('swal', [
                    'icon' => 'error',
                    'title' => 'Error',
                    'text' => 'El lote no fue encontrado.'
                ]);
                return response()->json(['message' => 'Lote no encontrado'], 404);
            }

            // Eliminar el lote
            $lote->delete();

            if($producto){
                $producto->stock -= $lote->cantidad; // Sumar la cantidad del lote al stock
                $producto->save();
            }

            session()->flash('swal', [
                'icon' => 'success',
                'title' => 'Eliminado',
                'text' => 'Lote eliminado correctamente.'
            ]);

            return response()->json(['message' => 'Lote eliminado exitosamente']);
        }catch (\Exception $e) {
            // Registrar el error y devolver una respuesta de error
            \Log::error('Error al eliminar el lote: ' . $e->getMessage());
            return response()->json(['message' => 'Error al eliminar el lote'], 500);
        
        }
    }

}
