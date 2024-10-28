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

Route::get('/', [GaleriaController::class, 'index'])->name('welcome');

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'store'])->name('user.store');
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('usuario.login');
// Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');

Route::get('/inicio', [InicioController::class, 'index'])->name('inicio');
// Auth::routes();
Route::get('/roles/{id}/permissions', [RolePermissionController::class, 'getPermisosPorRol'])->name('roles.permissions');

Route::resources([
    'users' => UserController::class,
    'productos' => ProductoController::class,
    'ventas' => VentasController::class,
]);

Route::middleware('auth')->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
});

Route::middleware(['auth', 'permission:abrir-caja'])->group(function () {
    Route::post('/caja/abrir', [CajaController::class, 'abrir'])->name('caja.abrir');
});

Route::middleware(['auth', 'permission:cerrar-caja'])->group(function () {
    Route::post('/caja/cerrar', [CajaController::class, 'cerrar'])->name('caja.cerrar');
});


Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


