<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\VentaController;

Route::get('/productos', [ProductController::class, 'index'])->name('api.productos.index');
Route::get('/ventas', [VentaController::class, 'index'])->name('api.ventas.index');
