<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Producto;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Lote;

class ProductController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:');
        $this->middleware('permission:ver-productos|agregar-producto|editar-producto|eliminar-producto', ['only' => ['index','show']]);
        $this->middleware('permission:agregar-producto', ['only' => ['create','store']]);
        $this->middleware('permission:editar-producto|modificar-precio', ['only' => ['edit','update']]);
        $this->middleware('permission:eliminar-producto', ['only' => ['destroy']]);
    }
    public function index()
    {

        $productos = Producto::all();

        return response()->json(
            $productos);
    }

    public function store(StoreProductRequest $request)
    {
    try {
        // Crear el lote
        $lote = Lote::create([
            'numero_lote' => $request->numero_lote,
            'fecha_vencimiento' => $request->fchVto,
        ]);

        // Crear el producto y asociarlo con el lote
        $producto = new Producto();
        $producto->codigo = $request->codigo;
        $producto->nombre = $request->nombre;
        $producto->unidad = $request->unidad;
        $producto->numero_lote = $lote->numero_lote;
        $producto->fchVto = $lote->fecha_vencimiento;
        $producto->precio_costo = $request->precio_costo;
        $producto->precio_venta = $request->precio_venta;
        $producto->iva = $request->iva;
        $producto->utilidad = $request->utilidad;
        $producto->descripcion = $request->descripcion ?? '';
        $producto->categoria_id = $request->categoria_id;
        $producto->stock = $request->stock;
        $producto->save();

        // Respuesta JSON de éxito
        return response()->json([
            'success' => true,
            'message' => 'Producto agregado exitosamente',
            'producto' => $producto
        ], 201);

    } catch (\Exception $e) {
        // Respuesta JSON en caso de error
        return response()->json([
            'success' => false,
            'message' => 'Hubo un error al agregar el producto',
            'error' => $e->getMessage()
        ], 500);
    }
}


    public function edit($codigo){

        $producto = Producto::where('codigo', $codigo)->first();
        return response()->json($producto);

    }


    public function update(UpdateProductRequest $request, $codigo){

        $producto = Producto::where('codigo', $codigo)->first();

        if($producto){
            $producto->update($request->all());
            session()->flash('swal', [
                'icon' => 'success',
                'title' => 'Actualizado',
                'text' => 'Producto actualizado correctamente'
            ]);
            return response()->json([
                'success' => true,
                'message' => 'Producto actualizado exitosamente',
                'producto' => $producto
            ]);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Producto no encontrado'
            ], 404);
        }

    }

    public function destroy($codigo){

        $producto = Producto::where('codigo', $codigo)->first();

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
