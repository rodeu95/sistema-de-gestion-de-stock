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

    public function edit(){
        $caja = Caja::find(1);
        $cajaAbierta = $caja ? $caja->estado:false;
        $productos = Producto::all();
        $categorias = Categoria::all();

        $productosBajoStockUN = Producto::where('unidad', 'UN')
            ->where('stock', '<=', 10)
            ->get();

        $productosBajoStockKG = Producto::where('unidad', 'KG')
            ->where('stock', '<=', 0.5)
            ->get();

        // Aquí puedes combinar los dos resultados si lo necesitas
        $bajoStock = $productosBajoStockUN->merge($productosBajoStockKG);

        return view('inventario.edit', compact('productos', 'cajaAbierta', 'bajoStock', 'categorias'));
    }

    public function update(Request $request){

        $validatedData = $request->validate([
            'producto_cod' => 'required|array',
            'producto_cod.*' => 'exists:productos,codigo',
            'cantidad' => 'required|array',
            'cantidad.*' => 'numeric|min:0.01'
        ]);
    
        foreach ($validatedData['producto_cod'] as $index => $productoCod) {
            $cantidad = $validatedData['cantidad'][$index];
            $producto = Producto::find($productoCod);
    
            // Update the product's stock
            $producto->stock += $cantidad;
            $producto->save();
        }
        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Actualizado',
            'text' => 'Inventario actualizado correctamente'
        ]);

        return response()->json([
            'message' => 'Inventario actualizado correctamente.',
            'success' => true,
            'producto_cod' => $producto->codigo,
        ]);
    }
}
