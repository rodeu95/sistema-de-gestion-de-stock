<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\PDF;

class PDFController extends Controller
{
    public function generatePDF()
    {
        $data = [
        'title' => 'Ficha del Usuario',
        'heading' => 'Datos Personales',
        'content' => ''
        ];

        $pdf = PDF::loadView('export.myPDF', $data);

        return $pdf->download('miarchivo.pdf');
    }
}
