<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommuneController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\RegionController;
use App\Http\Middleware\AuthTokenMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login']);

Route::middleware(AuthTokenMiddleware::class)->group(function () {
    Route::get('customers/search', [CustomerController::class, 'search']);
    Route::apiResource('region', RegionController::class)->only(['index', 'store', 'destroy']);
    Route::apiResource('customers', CustomerController::class)->only(['index', 'store', 'destroy']);
    Route::apiResource('commune', CommuneController::class)->only(['index', 'store', 'destroy']);
});
