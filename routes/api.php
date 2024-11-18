<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\VentaController;
Use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\InventoryController;


Route::get('/productos', [ProductController::class, 'index'])->name('api.productos.index');
Route::delete('/productos/{codigo}', [ProductController::class, 'destroy'])->name('api.productos.destroy');
Route::put('/productos/{codigo}/disable', [ProductController::class, 'disable'])->name('productos.disable');
Route::put('/productos/{codigo}/enable', [ProductController::class, 'enable'])->name('productos.enable');

Route::get('/ventas', [VentaController::class, 'index'])->name('api.ventas.index');
Route::post('/ventas', [VentaController::class, 'store'])->name('ventas.store');
Route::get('/ventas/{id}/edit', [VentaController::class, 'edit'])->name('ventas.edit');
Route::put('/ventas/{id}', [VentaController::class, 'update'])->name('ventas.update');
Route::delete('/ventas/{id}', [VentaController::class, 'destroy'])->name('api.ventas.destroy');

Route::get('/inventario', [InventoryController::class, 'index'])->name('api.inventario.index');
Route::put('/inventario/update', [InventoryController::class, 'update'])->name('api.inventario.update');
Route::get('/inventario/edit', [InventoryController::class, 'edit'])->name('inventario.edit');


