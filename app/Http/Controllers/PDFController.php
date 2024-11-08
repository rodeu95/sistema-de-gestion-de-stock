<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\PDF;
use App\Models\Producto;

class PDFController extends Controller
{
    public function generatePDF()
    {
        $productos = Producto::all();
        $data = [
        'title' => 'Productos',
        'heading' => 'Lista de Productos',
        'productos' => $productos,
        'content' => ''
        ];

        $pdf = PDF::loadView('export.myPDF', $data);

        return $pdf->download('productos.pdf');
    }
}
