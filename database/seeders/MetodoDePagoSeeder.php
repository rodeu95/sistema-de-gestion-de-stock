<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MetodoDePago;

class MetodoDePagoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MetodoDePago::create(['nombre' => 'Efectivo']);
        MetodoDePago::create(['nombre' => 'Débito']);
        MetodoDePago::create(['nombre' => 'Crédito']);
        MetodoDePago::create(['nombre' => 'Transferencia']);
    }
}
