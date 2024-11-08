<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Venta;

class VentaController extends Controller
{
    public function index()
    {
        $ventas = Venta::with('productos', 'metodoPago')->get(); // Carga las ventas con la relación 'productos'
        return response()->json($ventas);
    }
}
