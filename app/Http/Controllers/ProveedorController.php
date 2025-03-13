<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proveedor;
use App\Models\Caja;

class ProveedorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $proveedores = Proveedor::all();
        $caja = Caja::find(1);
        $cajaAbierta = $caja ? $caja->estado : false;

        return view('proveedores.index', compact('proveedores', 'cajaAbierta'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $caja = Caja::find(1);
        $cajaAbierta = $caja ? $caja->estado:false;
        
        return view('proveedores.create', compact( 'cajaAbierta'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $proveedor = new Proveedor();
        $proveedor->nombre = $request->nombre;
        $proveedor->contacto = $request->contacto;
        $proveedor->telefono = $request->telefono;
        $proveedor->email = $request->email;
        $proveedor->direccion = $request->direccion;
        $proveedor->cuit = $request->cuit;
        $proveedor->save();

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
