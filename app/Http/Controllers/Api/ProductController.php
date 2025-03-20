<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Producto;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Lote;
use Illuminate\Support\Facades\Log;
use App\Models\Caja;
use App\Models\Categoria;

class ProductController extends Controller
{

    public function __construct()

    {
        $this->middleware('auth:sanctum');
        $this->middleware('permission:ver-productos', ['only' => ['index','show']]);
        $this->middleware('permission:agregar-producto', ['only' => ['create','store']]);
        $this->middleware('permission:editar-producto', ['only' => ['edit','update', 'actualizarPrecios']]);
        $this->middleware('permission:deshabilitar-producto', ['only' => ['disable']]);
        $this->middleware('permission:habilitar-producto', ['only' => ['enable']]);
        $this->middleware('permission:ver-productos-vencidos', ['only' => ['vencidos']]);
        $this->middleware('permission:ver-productos-a-vencer', ['only' => ['porVencer']]);

    }
    public function index()
    {
        $productos = Producto::all();

        return response()->json($productos);
       
    }
    public function create(){
        $categorias = Categoria::all();
        $caja = Caja::find(1);
        $cajaAbierta = $caja ? $caja->estado:false;
        
        return view('productos.create', compact('categorias', 'cajaAbierta'));
    }
    
    public function show($codigo){
        $producto = Producto::where('codigo', $codigo)->first();
        if (!$producto) {
            return response()->json(['error' => 'Producto no encontrado'], 404);
        }

        $producto->estado = $producto->estado == 1 ? 'Activo' : 'Inactivo';
    
        return response()->json([
            'codigo' => $producto->codigo,
            'nombre' => $producto->nombre,
            'precio_venta' => $producto->precio_venta,
            'stock' => $producto->stock,
            'estado' => $producto->estado,
            'unidad' => $producto->unidad,
        ]);
    }

    public function store(StoreProductRequest $request)
    {
    try {
        // Crear el producto y asociarlo con el lote
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
            if ($request->unidad == 'UN' && floor($request->stock) != $request->stock) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se puede registrar un producto con unidad "UN" con stock menor a 1.'
                ], 400);  // Devuelve un error 400 (Bad Request)
            }else{
                $producto->update($request->all());
                session()->flash('swal', [
                    'icon' => 'success',
                    'title' => 'Actualizado',
                    'text' => 'Producto actualizado correctamente',
                    'confirmButtonColor' => "#aed5b6",
                ]);
                return response()->json([
                    'success' => true,
                    'message' => 'Producto actualizado exitosamente',
                    'producto' => $producto
                ]);
            }
            
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Producto no encontrado'
            ], 404);
        }

    }

    public function destroy($codigo){

        $user = Auth::user();
        if (!$user->can('eliminar-producto')) {
            return response()->json(['message' => 'No tienes permiso para eliminar este producto'], 403);
        }

        $producto = Producto::where('codigo', $codigo)->first();

        if($producto){
            $producto->delete();
            session()->flash('swal', [
                'icon' => 'success',
                'title' => 'Eliminado',
                'text' => 'Producto eliminado correctamente',
                'confirmButtonColor' => "#aed5b6",
            ]);
            return response()->json(['message' => 'Producto eliminado exitosamente']);
        }else{
            return response()->json(['message' => 'Producto no encontrado'], 404);
        }
    }

    public function disable($codigo)
    {
        $producto = Producto::where('codigo', $codigo)->first();

        if ($producto) {
            $producto->estado = false; // O el campo que uses para indicar deshabilitación
            $producto->save();

            return response()->json(['message' => 'Producto deshabilitado exitosamente']);
        }

        return response()->json(['message' => 'Producto no encontrado'], 404);
    }

    public function enable($codigo)
    {
        $producto = Producto::where('codigo', $codigo)->first();

        if ($producto) {
            $producto->estado = true; // O el campo que uses para indicar deshabilitación
            $producto->save();

            return response()->json(['message' => 'Producto habilitado exitosamente']);
        }

        return response()->json(['message' => 'Producto no encontrado'], 404);
    }

    public function vencidos()
    {
        Log::info('Entró al método vencidos');
        dd('entró al método');
        $productosVencidos = Producto::where('fchVto', '<', now())->get();
        return view('productos.vencidos', compact('productosVencidos'));

    }

    public function porVencer()
    {
        $productosProximosAVencer = Producto::where('fchVto', '>', now())
                                        ->where('fchVto', '<', now()->addDays(30)) // Productos que vencen en los próximos 30 días
                                        ->get();
        return view('productos.proximos_a_vencer', compact('productosProximosAVencer'));
    }

    public function actualizarPrecios(Request $request){
        \Log::info('Datos recibidos:', $request->all());
        $productos = Producto::all();
        $request->validate([
            'porcentaje' => 'required|numeric|min:0.01',
        ]);
        try{
            foreach($productos as $producto){
                $producto->update([
                    'precio_venta' => round($producto->precio_venta * (1 + ($request->porcentaje / 100)))
                ]);
            }
            session()->flash('swal', [
                'icon' => 'success',
                'title' => 'Actualizados',
                'text' => 'Precios actualizados correctamente',
                'confirmButtonColor' => "#aed5b6",
            ]);
            return response()->json([
                'success' => true,
                'message' => 'Precios actualizados exitosamente',
                'productos' => $productos
            ]);
        }catch(\Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'Hubo un error al actualizar los precios',
                'error' => $e->getMessage()
            ], 500);
        }
        
    }

}
