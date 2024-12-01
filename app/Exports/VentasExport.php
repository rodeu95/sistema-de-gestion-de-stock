<?php

namespace App\Exports;

use App\Models\Venta;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Carbon\Carbon;


class VentasExport implements FromView
{
    /**
     * Return a view for export
     *
     * @return View
     */
    public function view(): View
    {
        $startOfDay = Carbon::now()->startOfDay();
        $endOfDay = Carbon::now()->endOfDay();

        $ventas = Venta::whereBetween('fecha_venta', [$startOfDay, $endOfDay])->get();
        return view( 'export.myExcel',[
            'ventas' => $ventas
        ]);
    }

    
}
