<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Proveedor;
use App\Models\Caja;
use App\Models\Categoria;

class ProveedorController extends Controller
{

    public function __construct()

    {
        $this->middleware('auth:sanctum');
        $this->middleware('permission:ver-proveedores', ['only' => ['index']]);
        $this->middleware('permission:agregar-proveedor', ['only' => ['create','store']]);
        $this->middleware('permission:editar-proveedor', ['only' => ['edit','update']]);
        $this->middleware('permission:deshabilitar-proveedor', ['only' => ['disable']]);
        $this->middleware('permission:habilitar-proveedor', ['only' => ['enable']]);

    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $proveedores = Proveedor::all();
        return response()->json($proveedores);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $caja = Caja::find(1);
        $cajaAbierta = $caja ? $caja->estado:false;
        $categorias = Categoria::all();
        
        return view('proveedores.create', compact( 'cajaAbierta', 'categorias'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'contacto' => 'required|string|max:255',
            'telefono' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'direccion' => 'required|string|max:255',
            'cuit' => 'required|string|max:15|unique:proveedores',
            'categorias' => 'array', // Validación para categorías
            'categorias.*' => 'exists:categorias,id' // Cada categoría debe existir en la tabla 'categorias'
        ]);

        $proveedor = new Proveedor();
        $proveedor->nombre = $request->nombre;
        $proveedor->contacto = $request->contacto;
        $proveedor->telefono = $request->telefono;
        $proveedor->email = $request->email;
        $proveedor->direccion = $request->direccion;
        $proveedor->cuit = $request->cuit;
        $proveedor->save();

        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Proveedor agregado!',
            'text' => 'El proveedor se ha agregado correctamente',
            'confirmButtonColor' => "#acd8b5",
        ]);

        // Asignar categorías al proveedor
        if ($request->has('categorias')) {
            $proveedor->categorias()->attach($request->categorias);
        }

        return response()->json([
            'success' => true,
            'message' => 'Proveedor agregado exitosamente',
            'proveedor' => $proveedor
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $proveedor = Proveedor::with('categorias')->findOrFail($id);
        if (!$proveedor) {
            return response()->json(['error' => 'Proveedor no encontrado'], 404);
        }

        // Obtener los IDs de las categorías asociadas al proveedor
        $categoriasProveedor = $proveedor->categorias->pluck('id')->toArray();

        return response()->json([
            'categoriasProveedor' => $categoriasProveedor,
            'proveedor' => $proveedor,
        ]);
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $proveedor = Proveedor::where('id', $id)->first();
        if($proveedor){
            $proveedor->update($request->all());
                session()->flash('swal', [
                    'icon' => 'success',
                    'title' => 'Actualizado',
                    'text' => 'Proveedor actualizado correctamente',
                    'confirmButtonColor' => "#acd8b5",
                ]);
                return response()->json([
                    'success' => true,
                    'message' => 'Producto actualizado exitosamente',
                    'proveedor' => $proveedor
                ]);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Proveedor no encontrado'
            ], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function disable($id)
    {
        $proveedor = Proveedor::where('id', $id)->first();

        if ($proveedor) {
            $proveedor->estado = false; // O el campo que uses para indicar deshabilitación
            $proveedor->save();

            return response()->json(['message' => 'Proveedor deshabilitado exitosamente']);
        }

        return response()->json(['message' => 'Proveedor no encontrado'], 404);
    }

    public function enable($id)
    {
        $proveedor = Proveedor::where('id', $id)->first();

        if ($proveedor) {
            $proveedor->estado = true; // O el campo que uses para indicar deshabilitación
            $proveedor->save();

            return response()->json(['message' => 'Proveedor habilitado exitosamente']);
        }

        return response()->json(['message' => 'Proveedor no encontrado'], 404);
    }

    public function categorias($id)
    {
        $proveedor = Proveedor::findOrFail($id);
        return response()->json(['categorias' => $proveedor->categorias]);
    }

    public function getCategoriaPorProveedor($proveedorId)
    {
        // Buscar el proveedor y cargar sus categorías
        $proveedor = Proveedor::with('categorias')->find($proveedorId);

        if (!$proveedor) {
            return response()->json(['error' => 'Proveedor no encontrado'], 404);
        }

        // Obtener los IDs de las categorías asociadas al proveedor
        $categoriasProveedor = $proveedor->categorias;

        return response()->json([
            'categoriasProveedor' => $categoriasProveedor
        ]);
    }

    public function filtrarPorCategoria(Request $request)
    {
        $categoriaId = $request->input('categoria_id');

        if ($categoriaId) {
            $proveedores = Proveedor::whereHas('categorias', function ($query) use ($categoriaId) {
                $query->where('categorias.id', $categoriaId); // Importante especificar la tabla si hay ambigüedad
            })->get();
        } else {
            $proveedores = Proveedor::all();
        }

        return response()->json($proveedores);
    }




}
