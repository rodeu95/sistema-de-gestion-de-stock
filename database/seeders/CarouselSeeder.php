<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Carousel;

class CarouselSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $txt = '/img/galeria/imagen';
        Carousel::factory()->create([
        'url' => $txt.'01.jpg',
        'title' => '',
        'description' => '',
        'priority' => 1,
        ]);
        Carousel::factory()->create([
        'url' => $txt.'02.jpg',
        'title' => '',
        'description' => '',
        'priority' => 2,
        ]);
        Carousel::factory()->create([
        'url' => $txt.'03.jpg',
        'title' => '',
        'description' => '',
        'priority' => 3,
        ]);
        Carousel::factory()->create([
        'url' => $txt.'04.jpg',
        'title' => '',
        'description' => '',
        'priority' => 4,
        ]);
        Carousel::factory()->create([
        'url' => $txt.'05.jpg',
        'title' => '',
        'description' => '',
        'priority' => 5,
        ]);

    }
}
