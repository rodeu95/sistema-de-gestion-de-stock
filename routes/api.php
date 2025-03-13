<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\VentaController;
Use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\InventoryController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\Api\LoteController;
use App\Http\Controllers\Api\ProveedorController;


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
Route::put('/ventas/{id}/anular', [VentaController::class, 'anularVenta'])->name('api.ventas.anular');
Route::get('/ventas/{id}', [VentaController::class, 'show'])->name('api.ventas.show');

Route::get('/inventario', [InventoryController::class, 'index'])->name('api.inventario.index');
Route::put('/inventario/update/{codigo}', [InventoryController::class, 'update'])->name('api.inventario.update');
Route::get('/inventario/edit/{codigo}', [InventoryController::class, 'edit'])->name('api.inventario.edit');

Route::get('/proveedores', [ProveedorController::class, 'index'])->name('api.proveedores.index');
Route::post('/proveedores', [ProveedorController::class, 'store'])->name('api.proveedores.store');
Route::get('/proveedores/{id}/edit', [ProveedorController::class, 'edit'])->name('api.proveedores.edit');
Route::put('/proveedores/{id}', [ProveedorController::class, 'update'])->name('api.proveedores.update');
Route::delete('/proveedores/{id}', [ProveedorController::class, 'destroy'])->name('api.proveedores.destroy');
Route::put('/proveedores/{id}/disable', [ProveedorController::class, 'disable'])->name('proveedores.disable');
Route::put('/proveedores/{id}/enable', [ProveedorController::class, 'enable'])->name('proveedores.enable');

// Route::post('lote/store', [LoteController::class, 'store'])->name('api.lotes.store');
Route::get('lotes', [LoteController::class, 'index'])->name('api.lotes.index');
Route::delete('lotes/{numero_lote}', [LoteController::class, 'destroy'])->name('api.lotes.destroy');


