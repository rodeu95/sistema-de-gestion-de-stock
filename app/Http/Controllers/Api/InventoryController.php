<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Caja;
use App\Models\Categoria;

class InventoryController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware('permission:gestionar-inventario', ['only' => ['index', 'update', 'edit']]);
    }
    public function index()
    {

        $productos = Producto::with(['categoria', 'lotes'])
            ->where('estado', 1)
            ->get();

        return response()->json($productos); // Asegúrate de que siempre devuelva JSON
    }

    public function edit($codigo){
        $producto = Producto::where('codigo', $codigo)->first();
        return response()->json($producto);

    }

    public function update(Request $request, $codigo){

        $producto = Producto::where('codigo', $codigo)->first();
        $validatedData = $request->validate([
            'cantidad' => 'required|numeric|min:0.01',
        ]);
    // Update the product's stock
        $producto->stock += $validatedData['cantidad'];
        $producto->save();
        
        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Actualizado',
            'text' => 'Inventario actualizado correctamente'
        ]);

        return response()->json([
            'message' => 'Inventario actualizado correctamente.',
            'success' => true,
            'producto' => $producto,
        ]);
    }
}
