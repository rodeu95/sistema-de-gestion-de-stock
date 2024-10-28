<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Venta;
use App\Models\Caja;
use Illuminate\Support\Facades\Auth;

class InicioController extends Controller
{
    public function index()
    {

        $totalVentasHoy = Venta::whereDate('created_at', now())->count();
        $montoTotalHoy = Venta::whereDate('created_at', now())->sum('monto_total');
 
        $ventas = Venta::selectRaw('DATE(created_at) as date, SUM(monto_total) as total')
                            ->groupBy('date')
                            ->orderBy('date', 'desc')
                            ->limit(7)
                            ->get();

        
        $labels = $ventas->isEmpty() ? ['Día 1', 'Día 2', 'Día 3', 'Día 4', 'Día 5', 'Día 6', 'Día 7'] : $ventas->pluck('date');
        $data = $ventas->isEmpty() ? [0, 0, 0, 0, 0, 0, 0] : $ventas->pluck('total');

        $bajoStock = Producto::where('stock', '<=', 10)->get();
        $caja = Caja::find(1);
        $cajaAbierta = $caja ? $caja->estado:false;
        
        
        // Pasar los datos a la vista
        return view('dashboard.index', [
            'totalVentasHoy' => $totalVentasHoy,
            'montoTotalHoy' => $montoTotalHoy,
            'bajoStock' => $bajoStock,
            'cajaAbierta' => $cajaAbierta,
            'labels' => $labels,
            'data' => $data,
        ],);

    }
}
