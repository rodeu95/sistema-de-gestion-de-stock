<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Producto;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;

class ProductController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:editar-producto|modificar-precio', ['only' => ['edit','update']]);
        $this->middleware('permission:eliminar-producto', ['only' => ['destroy']]);
    }
    public function index()
    {
        // Obtener todos los productos
        $productos = Producto::all();


        // Retornar los productos como JSON
        return response()->json(
            $productos);
    }

    public function edit($codigo){

        $producto = Producto::where('codigo', $codigo)->first();
        return response()->json($producto);

    }


    public function update(UpdateProductRequest $request, Producto $producto){

        if($producto){
            $producto->update($request->all());
            session()->flash('swal', [
                'icon' => 'success',
                'title' => 'Actualizado',
                'text' => 'Producto actualizado correctamente'
            ]);
            return response()->json(['message' => 'Producto actualizado exitosamente', 'producto' => $producto]);
        }else{
            return response()->json(['message' => 'Producto no encontrado'], 404);
        }

    }

    public function destroy(Producto $producto){

        if($producto){
            $producto->delete();
            session()->flash('swal', [
                'icon' => 'success',
                'title' => 'Eliminado',
                'text' => 'Producto eliminado correctamente'
            ]);
            return response()->json(['message' => 'Producto eliminado exitosamente']);
        }else{
            return response()->json(['message' => 'Producto no encontrado'], 404);
        }
    }

}
