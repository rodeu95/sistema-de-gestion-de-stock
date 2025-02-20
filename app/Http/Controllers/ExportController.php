<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\PDF;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Caja;
use App\Models\Venta;

class ExportController extends Controller
{
    public function generatePDF()
    {
        $productos = Producto::all();
        $categorias = Categoria::all();
        $caja = Caja::find(1);
        $cajaAbierta = $caja ? $caja->estado:false;

        return view('export.productos',compact('productos', 'categorias', 'cajaAbierta'));
    }

    public function generateExcel()
    {
        $ventas = Venta::all();
        $caja = Caja::find(1);
        $cajaAbierta = $caja ? $caja->estado:false;

        return view('export.ventas',compact('ventas', 'cajaAbierta'));
    }
}
