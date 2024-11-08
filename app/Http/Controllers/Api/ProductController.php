<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Producto;

class ProductController extends Controller
{
    public function index()
    {
        // Obtener todos los productos
        $productos = Producto::all();


        // Retornar los productos como JSON
        return response()->json(
            $productos);
    }

}
