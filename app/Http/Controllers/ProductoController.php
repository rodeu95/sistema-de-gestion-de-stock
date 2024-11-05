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
use App\Exports\ProductsExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Lote;


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
     
    public function index(Request $request){

        $search = $request->input('search');

    // Filtramos los productos según el término de búsqueda
        $productos = Producto::when($search, function ($query, $search) {
            return $query->where('nombre', 'like', '%' . $search . '%');
        })->get();

        // Obtenemos el estado de la caja
        $caja = Caja::find(1);
        $cajaAbierta = $caja ? $caja->estado : false;

        return view('productos.index', compact('productos', 'cajaAbierta'));
        
    }
    public function create(){
        $categorias = Categoria::all();
        $caja = Caja::find(1);
        $cajaAbierta = $caja ? $caja->estado:false;
        
        return view('productos.create', compact('categorias', 'cajaAbierta'));
    }

    public function store(StoreProductRequest $request)
    {

        $lote = Lote::create([
            'numero_lote' => $request->numero_lote,
            'fecha_vencimiento' => $request->fchVto,
        ]);
        // dd($request->all());
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

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Nuevo producto',
            'text' => 'Producto agregado'
        ]);
        
        return redirect()->route('productos.index');
               
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
        $categorias = Categoria::all();
        $caja = Caja::find(1);
        $cajaAbierta = $caja ? $caja->estado:false;

        return view('productos.edit', [
            'producto' => $producto,
            'cajaAbierta' => $cajaAbierta,
            'categorias' => $categorias
        ]);
    }

    public function update(UpdateProductRequest $request, Producto $producto){
        $producto->update($request->all());
        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Actualizado',
            'text' => 'Producto actualizado correctamente'
        ]);
        return redirect()->back();
                // ->withSuccess('Product is updated successfully.');

    }

    public function destroy(Producto $producto){
        $producto->delete();
        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Eliminado',
            'text' => 'Producto eliminado correctamente'
        ]);
        return redirect()->route('productos.index');
                // ->withSuccess('Product is deleted successfully.');
    }

    public function export(){
        return Excel::download(new ProductsExport, 'productos.xlsx');
    }
        
}
