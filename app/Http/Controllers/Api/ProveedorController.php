<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
        return response()->json($proveedores);
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
        $proveedor = Proveedor::where('id', $id)->first();
        return response()->json($proveedor);
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
}
