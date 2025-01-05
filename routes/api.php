<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\VentaController;
Use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\InventoryController;
use App\Http\Controllers\ProductoController;


Route::get('/productos', [ProductController::class, 'index'])->name('api.productos.index');
Route::get('/productos/{codigo}', [ProductController::class, 'show'])->name('api.producto.show');
Route::put('/productos/{codigo}', [ProductController::class, 'update'])->name('api.productos.update');
Route::post('/productos', [ProductController::class, 'store'])->name('api.productos.store');
Route::put('/productos/{codigo}/disable', [ProductController::class, 'disable'])->name('productos.disable');
Route::put('/productos/{codigo}/enable', [ProductController::class, 'enable'])->name('productos.enable');

Route::get('/ventas', [VentaController::class, 'index'])->name('api.ventas.index');
Route::post('/ventas', [VentaController::class, 'store'])->name('api.ventas.store');
Route::get('/ventas/{id}/edit', [VentaController::class, 'edit'])->name('api.ventas.edit');
Route::put('/ventas/{id}', [VentaController::class, 'update'])->name('api.ventas.update');
Route::delete('/ventas/{id}', [VentaController::class, 'destroy'])->name('api.ventas.destroy');

Route::get('/inventario', [InventoryController::class, 'index'])->name('api.inventario.index');
Route::put('/inventario/update/{codigo}', [InventoryController::class, 'update'])->name('api.inventario.update');
Route::get('/inventario/edit/{codigo}', [InventoryController::class, 'edit'])->name('api.inventario.edit');


