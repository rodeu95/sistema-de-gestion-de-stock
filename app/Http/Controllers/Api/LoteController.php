<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Caja;
use App\Models\Lote;

class LoteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware('permission:gestionar-inventario', ['only' => ['store', 'destroy']]);
        $this->middleware('permission:ver-lotes', ['only' => ['index']]);
    }
    public function index(){

        $lotes = Lote::with('producto') 
        ->orderBy('fecha_ingreso', 'desc') 
        ->get();
        
        return response()->json($lotes);
    }

    public function store(Request $request)
    {

        // dd($request->all());
        $request->validate([
            'producto_cod' => 'required|exists:productos,codigo',
            'numero_lote' => 'required',
            'cantidad' => 'required|numeric|min:1',
            'fecha_ingreso' => 'nullable|date',
            'fecha_vencimiento' => 'requires|date|after_or_equal:fecha_ingreso',
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
            'text' => 'El lote se ha agregado correctamente',
            'confirmButtonColor' => "#aed5b6",
        ]);

        // Redirigir con mensaje de éxito
        return redirect()->back()->with('success', 'Lote agregado exitosamente.');
    }

    public function destroy($numero_lote)
    {
        $numero_lote = trim($numero_lote);

        try{
            $lote = Lote::where('numero_lote', $numero_lote)->first();;
                       
            if (!$lote) {
                \Log::info("Lote no encontrado");
                session()->flash('swal', [
                    'icon' => 'error',
                    'title' => 'Error',
                    'text' => 'El lote no fue encontrado.',
                    'confirmButtonColor' => "#aed5b6",
                ]);
                return response()->json(['message' => 'Lote no encontrado'], 404);
            }
            $cantidad = $lote->cantidad;

            $producto = Producto::where('codigo', $lote->producto_cod)->first();
            
            \Log::debug($producto);
            if($producto){
                $producto->stock -= $cantidad; // Sumar la cantidad del lote al stock
                $producto->save();
            }

            $lote->delete();

            session()->flash('swal', [
                'icon' => 'success',
                'title' => 'Eliminado',
                'text' => 'Lote eliminado correctamente.',
                'confirmButtonColor' => "#aed5b6",
            ]);

            return response()->json(['message' => 'Lote eliminado exitosamente']);
        }catch (\Exception $e) {
            // Registrar el error y devolver una respuesta de error
            \Log::error('Error al eliminar el lote: ' . $e->getMessage());
            return response()->json(['message' => 'Error al eliminar el lote'], 500);
        
        }
        
    }
}
