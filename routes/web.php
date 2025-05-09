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
use App\Http\Controllers\ExportController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\VencimientosController;
use App\Http\Controllers\LoteController;
use App\Http\Controllers\ProveedorController;


Auth::routes();
Route::get('/', [GaleriaController::class, 'index'])->name('welcome');

Route::get('/register/form', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/user/registro', [AuthController::class, 'store'])->name('user.store');
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/user/login', [AuthController::class, 'login'])->name('usuario.login');
// Rutas para manejo de contraseñas
Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');


Route::middleware(['auth'])->group(function () {
Route::get('/inicio', [InicioController::class, 'index'])->name('inicio');
});

Route::post('lote/store', [LoteController::class, 'store'])->name('lotes.store');
Route::get('lotes', [LoteController::class, 'index'])->name('lotes.index');
// Route::delete('lotes/{id}/destroy', [LoteController::class, 'destroy'])->name('lotes.destroy');

Route::get('/roles/{id}/permissions/{userId}', [RolePermissionController::class, 'getPermisosPorRolEdit'])->name('roles.permissions.edit');
Route::get('/roles/{id}/permissions', [RolePermissionController::class, 'getPermisosPorRol'])->name('roles.permissions');

Route::resources([
    'users' => UserController::class,
    'productos' => ProductoController::class,
    'ventas' => VentasController::class,
    'proveedores' => ProveedorController::class,
]);

Route::get('/proveedores/filtrar', [ProveedorController::class, 'filtrarPorCategoria']);

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

Route::get('/generate-pdf', [ProductoController::class, 'export'])->name('generate-pdf');
Route::get('/export/productos', [ExportController::class, 'generatePDF'])->name('productos.export');
Route::get('/export/ventas', [ExportController::class, 'generateExcel'])->name('ventas.export');
Route::get('/generate-excel', [VentasController::class, 'export'])->name('generate-excel');
Route::get('/export/inventario', [InventarioController::class, 'export'])->name('export.inventario');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
