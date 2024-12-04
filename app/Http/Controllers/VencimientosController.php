<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Caja;

class VencimientosController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
        $this->middleware('permission:ver-productos-vencidos', ['only' => ['vencidos']]);
        $this->middleware('permission:ver-productos-a-vencer', ['only' => ['porVencer']]);
    }

    public function vencidos()
    {
        $caja = Caja::find(1);
        $cajaAbierta = $caja ? $caja->estado:false;
        $productosVencidos = Producto::where('fchVto', '<', now())->get();
        if ($productosVencidos->isEmpty()) {
            abort(404, 'No se encontraron productos vencidos');
        }
        return view('vencimientos.vencidos', compact('productosVencidos', 'cajaAbierta'));
    }


    public function porVencer(){
        $caja = Caja::find(1);
        $cajaAbierta = $caja ? $caja->estado:false;

        $productosProximosAVencer = Producto::whereBetween('fchVto', [now(), now()->addDays(30)])->get();
        return view('vencimientos.proximos_a_vencer', compact('productosProximosAVencer', 'cajaAbierta'));
    }

}
