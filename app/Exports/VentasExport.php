<?php

namespace App\Exports;

use App\Models\Venta;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class VentasExport implements FromView, WithEvents
{
    /**
     * Return a view for export
     *
     * @return View
     */
    protected $ventas;

    public function __construct($ventas)
    {
        $this->ventas = $ventas;
    }

    public function view(): View
    {
        return view('export.myExcel', ['ventas' => $this->ventas]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Establecer ancho de columnas
                $sheet->getColumnDimension('A')->setWidth(25); // Método de pago
                $sheet->getColumnDimension('B')->setWidth(15); // Monto total
                $sheet->getColumnDimension('C')->setWidth(20); // Fecha de venta

                // Combinar celdas para el título si es necesario
                $sheet->mergeCells('A1:C1');
                $sheet->setCellValue('A1', 'Reporte de Ventas');

                // Aplicar estilo al título
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 14],
                    'alignment' => ['horizontal' => 'center'],
                ]);

                $sheet->getStyle('A2:C' . $sheet->getHighestRow())
                      ->getAlignment()
                      ->setHorizontal(Alignment::HORIZONTAL_LEFT);

                      $sheet->getStyle('A1:C' . $sheet->getHighestRow())->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                                'color' => ['argb' => '000000'],
                            ],
                        ],
                    ]);
            }
        ];
    }

    
}
