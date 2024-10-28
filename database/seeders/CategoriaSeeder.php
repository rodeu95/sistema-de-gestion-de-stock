<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Categoria;

class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categorias = [
            ['nombre' => 'Bebidas'],
            ['nombre' => 'Conservas'],
            ['nombre' => 'Snacks'],
            ['nombre' => 'Golosinas'],
            ['nombre' => 'Limpieza'],
            ['nombre' => 'Fiambres'],
            ['nombre' => 'Bebidas alcohólicas'],
            ['nombre' => 'Perfumería'],
            ['nombre' => 'No perecederos'],
            ['nombre' => 'Cuidado bucal'],
            ['nombre' => 'Higiene personal'],
            ['nombre' => 'Pañalería'],
            ['nombre' => 'Panadería'],
            ['nombre' => 'Lácteos'],
        ];

        foreach ($categorias as $categoria) {
            Categoria::create($categoria);
        }
    }
}
