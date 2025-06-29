<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProductosController;
use App\Http\Controllers\RegistrosSalidaController;
use App\Http\Controllers\RegistrosEntradaController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\UsuariosController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::name('api.spa.')  // Asignar un prefijo al nombre de las rutas
    ->prefix('spa')      // Usar el prefijo 'spa' en las URLs
    ->group(function () {
        // Definir las rutas para cada controlador usando apiResource
        Route::apiResource('login',LoginController::class);
        Route::apiResource('productos',ProductosController::class);
        Route::apiResource('reporte',ReporteController::class);
        Route::apiResource('registroentrada',RegistrosEntradaController::class);
        Route::apiResource('registrosalida',RegistrosSalidaController::class);
        Route::apiResource('usuarios',UsuariosController::class);
    });