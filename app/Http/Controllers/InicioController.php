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

        $topProductos = Producto::select('nombre', \DB::raw('SUM(venta_producto.cantidad) as total_vendido'))
        ->join('venta_producto', 'productos.codigo', '=', 'venta_producto.producto_cod')
        ->groupBy('productos.nombre')
        ->orderByDesc('total_vendido')
        ->limit(3)
        ->get();

        $labelsTop = $topProductos->pluck('nombre');
        $dataTop = $topProductos->pluck('total_vendido');
       
        // Contar las ventas de hoy y calcular el monto total de hoy
        $totalVentasHoy = Venta::whereBetween('fecha_venta', [$today, $endOfToday])->count();
        $montoTotalHoy = Venta::whereBetween('fecha_venta', [$today, $endOfToday])->sum('monto_total');
 
        $sevenDaysAgo = Carbon::now()->subDays(6)->startOfDay();
        $ventas = Venta::whereBetween('fecha_venta', [$sevenDaysAgo, $endOfToday])
                       ->selectRaw('DATE(fecha_venta) as date, SUM(monto_total) as total')
                       ->groupBy('date')
                       ->orderBy('date', 'desc')
                       ->get();
        
        $labels = collect();
        $data = collect();

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->toDateString();
            $labels->push($date);

            // Buscar la venta para la fecha actual
            $venta = $ventas->firstWhere('date', $date);
            $data->push($venta ? $venta->total : 0);
        }

        $productosProximosAVencer = Producto::whereBetween('fchVto', [now(), now()->addDays(30)])
        ->where('estado', 1)
        ->get();
        $productosVencidos = Producto::where('fchVto', '<', now())
        ->where('estado', 1)
        ->get();

        // $productosBajoStockUN = Producto::where('unidad', 'UN')
        //     ->where('stock', '<=', 10)
        //     ->get();

        // $productosBajoStockKG = Producto::where('unidad', 'KG')
        //     ->where('stock', '<=', 0.5)
        //     ->get();

        
        $bajoStock = Producto::whereColumn('stock', '<=', 'stock_minimo')
            ->get();

        
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
            'productosProximosAVencer' => $productosProximosAVencer,
            'productosVencidos' => $productosVencidos,
            'topProductos' => $topProductos,
            'labelsTop' => $labelsTop,
            'dataTop' => $dataTop
        ],);

    }
}
