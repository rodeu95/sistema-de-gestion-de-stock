<?php

namespace App\Exports;

use App\Models\Producto;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductsExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Producto::select("codigo", "nombre", "fchVto", "stock")->get();
    }

    public function headings(): array
    {
    return ["Código", "Nombre", "Fecha de Vencimiento", "Stock"];
    }

}
