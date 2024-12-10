<?php

use App\Http\Controllers\InicioController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GaleriaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\VentasController;
use App\Http\Controllers\CajaController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\VencimientosController;

Route::get('/', [GaleriaController::class, 'index'])->name('welcome');

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/user/register', [AuthController::class, 'store'])->name('user.store');
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/user/login', [AuthController::class, 'login'])->name('usuario.login');

Route::middleware(['auth'])->group(function () {
Route::get('/inicio', [InicioController::class, 'index'])->name('inicio');
});


Route::get('/roles/{id}/permissions', [RolePermissionController::class, 'getPermisosPorRol'])->name('roles.permissions');

Route::resources([
    'users' => UserController::class,
    'productos' => ProductoController::class,
    'ventas' => VentasController::class,
]);

Route::get('/productos-vencidos', [VencimientosController::class, 'vencidos'])->name('productos.vencidos');
Route::get('/productos-por-vencerse', [VencimientosController::class, 'porVencer'])->name('productos.por-vencer');

Route::get('/inventario/edit', [InventarioController::class, 'edit'])->name('inventario.edit');
Route::get('/inventario', [InventarioController::class, 'index'])->name('inventario.index');
Route::put('/inventario/update', [InventarioController::class, 'update'])->name('inventario.update');

Route::middleware('auth')->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
});

Route::middleware(['auth', 'permission:abrir-caja'])->group(function () {
    Route::post('/caja/abrir', [CajaController::class, 'abrir'])->name('caja.abrir');
});

Route::middleware(['auth', 'permission:cerrar-caja'])->group(function () {
    Route::post('/caja/cerrar', [CajaController::class, 'cerrar'])->name('caja.cerrar');
});

Route::middleware(['auth', 'permission:ver-total-caja'])->group(function () {
    Route::get('/caja/total', [CajaController::class, 'total'])->name('caja.total');
});

Route::get('/generate-pdf', [PDFController::class, 'generatePDF'])->name('generate-pdf');

Route::controller(ProductoController::class)->group(function(){
    Route::get('/productos', 'index')->name('productos.index');
    Route::get('/productos-export', 'export')->name('productos.export');
});

Route::controller(VentasController::class)->group(function(){
    Route::get('/ventas', 'index')->name('ventas.index');
    Route::get('/ventas-export', 'export')->name('ventas.export');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});