<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;

Route::get('/productos', [ProductController::class, 'index']);
