<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\VentaController;

Route::get('/productos', [ProductController::class, 'index'])->name('api.productos.index');
Route::delete('/productos/{codigo}', [ProductController::class, 'destroy'])->name('api.productos.destroy');

Route::get('/ventas', [VentaController::class, 'index'])->name('api.ventas.index');
Route::get('/ventas/{id}/edit', [VentaController::class, 'edit'])->name('api.ventas.edit');
Route::delete('/ventas/{id}', [VentaController::class, 'destroy'])->name('api.ventas.destroy');

