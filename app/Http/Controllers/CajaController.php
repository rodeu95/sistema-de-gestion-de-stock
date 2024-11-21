<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Caja;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use App\Models\Venta;
use App\Models\Producto;
use App\Models\User;
use Carbon\Carbon;


class CajaController extends Controller
{

    public function __construct() {
        $this->middleware('auth');
        $this->middleware('permission:abrir-caja', ['only'=>'abrir']);
        $this->middleware('permission:cerrar-caja', ['only'=>'cerrar']);
        $this->middleware('permission:ver-total-caja', ['only'=>'total']);
    }
    

    public function abrir()
    {
  
        
        Caja::updateOrCreate(['id' => 1], ['estado' => true]);

        return redirect()->route('inicio')->with('info', 'Caja abierta')->with('user', Auth::user());

    }

    public function cerrar()
    {
    
    // Cambiar el estado de la caja
        Caja::updateOrCreate(['id' => 1], ['estado' => false]);

        return redirect()->route('inicio')->with('info', 'Caja cerrada. No puede registrar más ventas.')->with('user', Auth::user());

    }

    public function total(){
        $caja = Caja::find(1);
        $cajaAbierta = $caja ? $caja->estado:false;

        $today = Carbon::now()->startOfDay();
        $endOfToday = Carbon::now()->endOfDay();

        $totalVentasHoy = Venta::whereBetween('fecha_venta', [$today, $endOfToday])
        ->where('metodo_pago_id', 1)
        ->get();
        $montoTotalHoy = $totalVentasHoy->sum('monto_total'); 

        return view('caja.total', [
            'totalVentasHoy' => $totalVentasHoy,
            'montoTotalHoy' => $montoTotalHoy,
            'cajaAbierta' => $cajaAbierta,
        ]);
    }

}
