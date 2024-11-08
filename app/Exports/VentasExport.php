<?php

namespace App\Exports;

use App\Models\Venta;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;


class VentasExport implements FromView
{
    /**
     * Return a view for export
     *
     * @return View
     */
    public function view(): View
    {
        $ventas = Venta::all();
        return view( 'export.myExcel',[
            'ventas' => $ventas
        ]);
    }

    
}
