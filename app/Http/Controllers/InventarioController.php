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
        $this->middleware('permission:gestionar-inventario', ['only' => ['index']]);
    }

    public function index()
    {
        
        $caja = Caja::find(1);
        $cajaAbierta = $caja ? $caja->estado:false;
        $productos = Producto::with('lotes', 'categoria')->get();
        // dd($productos);
        return view('inventario.index', compact('productos', 'cajaAbierta'));
    }

    public function edit(){
        $caja = Caja::find(1);
        $cajaAbierta = $caja ? $caja->estado:false;
        $productos = Producto::all();

        return view('inventario.edit', compact('productos', 'cajaAbierta'));
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
        return redirect()->back();
    }
    
}
