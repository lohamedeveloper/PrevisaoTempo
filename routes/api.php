<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PrevisaoClimaController;

Route::get('get/store/return', [PrevisaoClimaController::class, 'getAll']);
Route::post('store', [PrevisaoClimaController::class, 'store']);
Route::get('consulta/{data}', [PrevisaoClimaController::class, 'search']);