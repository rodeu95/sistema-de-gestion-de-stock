<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Venta;
use App\Models\Caja;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class InicioController extends Controller
{
    public function index()
    {

        $today = Carbon::now()->startOfDay();
        $endOfToday = Carbon::now()->endOfDay();
        
        // Contar las ventas de hoy y calcular el monto total de hoy
        $totalVentasHoy = Venta::whereBetween('fecha_venta', [$today, $endOfToday])->count();
        $montoTotalHoy = Venta::whereBetween('fecha_venta', [$today, $endOfToday])->sum('monto_total');
 
        $sevenDaysAgo = Carbon::now()->subDays(6)->startOfDay();
        $ventas = Venta::whereBetween('fecha_venta', [$sevenDaysAgo, $endOfToday])
                       ->selectRaw('DATE(fecha_venta) as date, SUM(monto_total) as total')
                       ->groupBy('date')
                       ->orderBy('date', 'desc')
                       ->get();
        
        // Crear labels y datos para el gráfico de los últimos 7 días
        $labels = collect();
        $data = collect();

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->toDateString();
            $labels->push($date);

            // Buscar la venta para la fecha actual
            $venta = $ventas->firstWhere('date', $date);
            $data->push($venta ? $venta->total : 0);
        }

        $productosBajoStockUN = Producto::where('unidad', 'UN')
            ->where('stock', '<=', 10)
            ->get();

        $productosBajoStockKG = Producto::where('unidad', 'KG')
            ->where('stock', '<=', 0.5)
            ->get();

        // Aquí puedes combinar los dos resultados si lo necesitas
        $bajoStock = $productosBajoStockUN->merge($productosBajoStockKG);

        
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
