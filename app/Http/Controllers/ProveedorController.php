<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proveedor;
use App\Models\Caja;
use App\Models\Categoria;

class ProveedorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:ver-proveedores', ['only' => ['index']]);
        $this->middleware('permission:agregar-proveedor', ['only' => ['create','store']]);
        $this->middleware('permission:editar-proveedor', ['only' => ['edit','update']]);

    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $proveedores = Proveedor::all();
        $caja = Caja::find(1);
        $cajaAbierta = $caja ? $caja->estado : false;
        $categorias = Categoria::all();

        return view('proveedores.index', compact('proveedores', 'cajaAbierta', 'categorias'));
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
        try{
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
                'confirmButtonColor' => "#aed5b6",
            ]);

            // Asignar categorías al proveedor
            if ($request->has('categorias')) {
                $proveedor->categorias()->attach($request->categorias);
            }
        }catch(\Exception $e){
            session()->flash('swal', [
                'icon' => 'error',
                'title' => 'Error al agregar al proveedor',
                'text' => 'El proveedor no se ha agregado correctamente',
                'confirmButtonColor' => "#aed5b6",
            ]);
        }
        

        return redirect()->route('proveedores.index');
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
