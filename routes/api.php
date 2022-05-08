<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\CuentasController;
use App\Http\Controllers\MovimientosController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::prefix('Cliente')->group(function () {
    route::post('/login',[ClienteController::class,'index']);

});
Route::prefix('Cuentas')->group(function () {
    route::post('/lista',[CuentasController::class,'index']);
    route::post('/relacionar',[CuentasController::class,'RelacionarCuenta']);
    route::post('/terceros',[CuentasController::class,'cuentasterceros']);
});
Route::prefix('Movimientos')->group(function () {
    route::post('/',[MovimientosController::class,'index']);
    route::post('/movimientos',[MovimientosController::class,'movimiento']);
    route::get('/bancos',[MovimientosController::class,'bancos']);
    route::post('/generar',[MovimientosController::class,'generarqr']);
    route::post('/pagar',[MovimientosController::class,'pagarconqr']);
});