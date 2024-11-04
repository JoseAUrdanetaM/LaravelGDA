<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommuneController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\RegionController;
use App\Http\Middleware\AuthTokenMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Ruta para el inicio de sesión, se generará token que dura 1 hora al logear acorde al user_id
Route::post('login', [AuthController::class, 'login']);

// Agrupa las rutas que requieren autenticación mediante un token sha1
Route::middleware(AuthTokenMiddleware::class)->group(function () {
    // Ruta para buscar clientes acordes a su DNI o correo
    Route::get('customers/search', [CustomerController::class, 'search']);

    //Crud de Region, Customers, Comune - eliminando la opción de PUT
    Route::apiResource('region', RegionController::class)->only(['index', 'show', 'store', 'destroy']);
    Route::apiResource('customers', CustomerController::class)->only(['index', 'show', 'store', 'destroy']);
    Route::apiResource('commune', CommuneController::class)->only(['index', 'show', 'store', 'destroy']);
});
