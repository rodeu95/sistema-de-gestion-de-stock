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
use Barryvdh\DomPDF\Facade\PDF;


class ProductoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:ver-productos|agregar-producto|editar-producto|eliminar-producto', ['only' => ['index','show']]);
        $this->middleware('permission:agregar-producto', ['only' => ['create','store']]);
        $this->middleware('permission:editar-producto|modificar-precio', ['only' => ['edit','update']]);
        $this->middleware('permission:eliminar-producto', ['only' => ['destroy']]);
        $this->middleware('permission:exportar-archivos', ['only' => ['export']]);
        
    }
     
    public function index(Producto $producto){

        $productos = Producto::all();
        $categorias = Categoria::all();
        // Obtenemos el estado de la caja
        $caja = Caja::find(1);
        $cajaAbierta = $caja ? $caja->estado : false;

        return view('productos.index', compact('productos', 'producto', 'cajaAbierta', 'categorias'));
        
    }
    public function create(){
        $categorias = Categoria::all();
        $caja = Caja::find(1);
        $cajaAbierta = $caja ? $caja->estado:false;
        
        return view('productos.create', compact('categorias', 'cajaAbierta'));
    }

    public function store(StoreProductRequest $request)
    {
        $producto = new Producto();
        $producto->codigo = $request->codigo;
        $producto->nombre = $request->nombre;
        $producto->unidad = $request->unidad;
        $producto->precio_costo = $request->precio_costo;
        $producto->precio_venta = $request->precio_venta;
        $producto->iva = $request->iva;
        $producto->utilidad = $request->utilidad;
        $producto->descripcion = $request->descripcion ?? '';
        $producto->categoria_id = $request->categoria_id;
        // $producto->stock = $request->stock;
        $producto->stock_minimo = $request->stock_minimo;

        $producto->save();

        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Producto agregado!',
            'text' => 'El producto se ha agregado correctamente'
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
        $codigo = $producto->codigo;
        // $producto = Producto::where('codigo', $codigo)->first();
    
        if (!$producto) {
            return response()->json(['message' => 'Producto no encontrado'], 404);
        }

        return response()->json($producto);

        // return view('productos.edit', [
        //     'producto' => $producto,
        //     'cajaAbierta' => $cajaAbierta,
        //     'categorias' => $categorias
        // ]);
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


    public function export(Request $request){
        $categoria = $request->input('categoria');
        $bajoStock = $request->input('bajo_stock');
        $topProductos = $request->input('top_productos');
                // Filtrar los productos
        $productos = Producto::query();

        if ($categoria) {
            $productos->where('categoria_id', $categoria);
        }

        if ($bajoStock) {
            $productos->whereColumn('stock', '<', 'stock_minimo');
        }

        if ($topProductos) {
            $productos->join('venta_producto', 'productos.codigo', '=', 'venta_producto.producto_cod')
                ->select('productos.codigo',
                'productos.nombre',
                'productos.categoria_id',
                'productos.stock', 
                \DB::raw('SUM(venta_producto.cantidad) as total_vendido'))
                ->groupBy('productos.codigo', 
                'productos.nombre', 
                'productos.categoria_id', 
                'productos.stock') 
                ->orderByDesc('total_vendido')
                ->limit(3);
        }
        

        $productos = $productos->get();

        // Generar el PDF
        $pdf = PDF::loadView('export.myPDF', compact('productos'));

        // Descargar el archivo PDF
        return $pdf->download('productos_filtrados.pdf');
    }
}
