<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Lote;
use App\Models\Caja;

class InventarioController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:gestionar-inventario', ['only' => ['index', 'updateStock']]);
    }

    public function index()
    {
        $caja = Caja::find(1);
        $cajaAbierta = $caja ? $caja->estado:false;
        $productos = Producto::with('lotes')->get();
        return view('inventario.index', compact('productos', 'cajaAbierta'));
    }

    public function updateStock(Request $request, $id)
    {
        $request->validate([
            'stock' => 'required|integer|min:0'
        ]);

        $producto = Producto::findOrFail($id);
        
        $producto->save();

        return redirect()->route('inventario.index')->with('success', 'Stock actualizado exitosamente.');
    }

    
    public function reduceStock($productoId, $cantidadVendida)
    {
        $producto = Producto::findOrFail($productoId);

        if ($producto->stock >= $cantidadVendida) {
            $producto->stock -= $cantidadVendida;
            $producto->save();
            return response()->json(['message' => 'Stock reducido exitosamente'], 200);
        } else {
            return response()->json(['error' => 'Stock insuficiente'], 400);
        }
    }
}
