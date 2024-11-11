<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Venta;
use App\Models\Producto;

class VentaController extends Controller
{
    public function index()
    {
        $productos = Producto::all();
        $ventas = Venta::with('productos', 'metodoPago')->get(); // Carga las ventas con la relación 'productos'
        return response()->json(['ventas' => $ventas, 'productos' => $productos]);
    }

    public function edit($id)
    {
        $venta = Venta::with(['productos', 'metodoPago'])->findOrFail($id);
        $productos = Producto::all(); // Retrieve all products

        return response()->json(['venta' => $venta, 'productos' => $productos]);
    }
}
