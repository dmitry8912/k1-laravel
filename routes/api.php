<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::post('/login', [\App\Http\Controllers\LoginController::class, 'login']);

Route::middleware('auth:api')->group(function() {
    Route::prefix('credentials')->group(function() {
       Route::get('/', [\App\Http\Controllers\CredentialsController::class, 'all']);
       Route::post('/', [\App\Http\Controllers\CredentialsController::class, 'add']);
       Route::put('/{id}', [\App\Http\Controllers\CredentialsController::class, 'update']);
       Route::delete('/{id}', [\App\Http\Controllers\CredentialsController::class, 'delete']);
    });

    Route::prefix('gateways')->group(function() {
        Route::get('/', [\App\Http\Controllers\GatewaysController::class, 'all']);
        Route::post('/', [\App\Http\Controllers\GatewaysController::class, 'add']);
        Route::put('/{id}', [\App\Http\Controllers\GatewaysController::class, 'update']);
        Route::delete('/{id}', [\App\Http\Controllers\GatewaysController::class, 'delete']);
    });

    Route::prefix('endpoints')->group(function() {
        Route::get('/', [\App\Http\Controllers\EndpointsController::class, 'all']);
        Route::post('/', [\App\Http\Controllers\EndpointsController::class, 'add']);
        Route::put('/{id}', [\App\Http\Controllers\EndpointsController::class, 'update']);
        Route::delete('/{id}', [\App\Http\Controllers\EndpointsController::class, 'delete']);
        Route::prefix('direct')->group(function() {
            Route::get('/lock/{id}', [\App\Http\Controllers\EndpointsController::class, 'connect']);
            Route::get('/extend/{id}', [\App\Http\Controllers\EndpointsController::class, 'extend']);
            Route::get('/info/{id}', [\App\Http\Controllers\EndpointsController::class, 'lockInfo']);
            Route::get('/unlock/{id}', [\App\Http\Controllers\EndpointsController::class, 'unlock']);
        });
        Route::prefix('bookings')->group(function() {
            Route::get('/add/{id}', [\App\Http\Controllers\EndpointsController::class, 'addBooking']);
            Route::get('/delete/{id}', [\App\Http\Controllers\EndpointsController::class, 'deletBooking']);
        });
    });
});
