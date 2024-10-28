<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Caja;
use Spatie\Permission\Contracts\Permission;

class ProductoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:ver-productos|agregar-producto|editar-producto|eliminar-producto', ['only' => ['index','show']]);
        $this->middleware('permission:agregar-producto', ['only' => ['create','store']]);
        $this->middleware('permission:editar-producto|modificar-precio', ['only' => ['edit','update']]);
        $this->middleware('permission:eliminar-producto', ['only' => ['destroy']]);
    }
     
    public function index(){

        $productos = Producto::all();
        $caja = Caja::find(1);
        $cajaAbierta = $caja ? $caja->estado:false;
        return view('productos.index',compact('productos', 'cajaAbierta'));
        
    }
    public function create(){
        $categorias = Categoria::all();
        $caja = Caja::find(1);
        $cajaAbierta = $caja ? $caja->estado:false;
        
        return view('productos.create', compact('categorias', 'cajaAbierta'));
    }

    public function store(StoreProductRequest $request)
    {

        $data = $request->all();
        $data['descripcion'] = $data['descripcion'] ?? '';

        $producto = new Producto();
        $producto->codigo = $request->codigo;
        $producto->nombre = $request->nombre;
        $producto->descripcion = $request->descripcion;
        $producto->precio = $request->precio;
        $producto->stock = $request->stock;
        $producto->fchVto = $request->fchVto;
        $producto->categoria_id = $request->categoria_id;
        
        $producto->save();
        return redirect()->route('productos.index')
                ->withSuccess('New product is added successfully.');
    }

    public function show(Producto $producto){

        $caja = Caja::find(1);
        $cajaAbierta = $caja ? $caja->estado:false;
        return view('productos.show', [
            'producto' => $producto,
            'cajaAbierta' => $cajaAbierta,
        ]);
    }

    public function edit(Producto $producto){
        $caja = Caja::find(1);
        $cajaAbierta = $caja ? $caja->estado:false;

        return view('productos.edit', [
            'product' => $producto,
            'cajaAbierta' => $cajaAbierta,
        ]);
    }

    public function update(UpdateProductRequest $request, Producto $producto){
        $producto->update($request->all());
        return redirect()->back()
                ->withSuccess('Product is updated successfully.');

    }

    public function destroy(Producto $producto){
        $producto->delete();
        return redirect()->route('productos.index')
                ->withSuccess('Product is deleted successfully.');
    }
}
