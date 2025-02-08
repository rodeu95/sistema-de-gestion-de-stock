<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Caja;
use App\Models\Lote;

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
        $loteVencido = Lote::where('fecha_vencimiento', '<', now())
        ->get();
        return view('vencimientos.vencidos', compact('loteVencido', 'cajaAbierta'));
    }


    public function porVencer(){
        $caja = Caja::find(1);
        $cajaAbierta = $caja ? $caja->estado:false;

        $lotesProximosAVencer = Lote::whereBetween('fecha_vencimiento', [now(), now()->addDays(30)])
        ->get();
        return view('vencimientos.proximos_a_vencer', compact('lotesProximosAVencer', 'cajaAbierta'));
    }

}
