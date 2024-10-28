<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Carousel;

class GaleriaController extends Controller
{
    public function index()
    {
        // Obtener todas las imágenes de la tabla `carousels` ordenadas por prioridad
        $slides = Carousel::orderBy('priority')->get();
        // Retornar la vista `galeria.index` con las imágenes
        return view('galeria.index', compact('slides'));
    }
}
