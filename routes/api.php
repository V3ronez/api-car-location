<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\CarModelController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\LocationController;
use Illuminate\Auth\Events\Login;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/* |-------------------------------------------------------------------------- | API Routes |-------------------------------------------------------------------------- | | Here is where you can register API routes for your application. These | routes are loaded by the RouteServiceProvider within a group which | is assigned the "api" middleware group. Enjoy building your API! |
 */

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('login', ['App\Http\Controllers\AuthController', 'login']);
Route::post('refresh', ['App\Http\Controllers\AuthController', 'refresh']);

Route::prefix('v1')->middleware('jwt.auth')->group(function () {
    Route::post('me', ['App\Http\Controllers\AuthController', 'me']);
    Route::post('logout', ['App\Http\Controllers\AuthController', 'logout']);

    Route::apiResource('brand', BrandController::class);
    Route::apiResource('car', CarController::class);
    Route::apiResource('car-model', CarModelController::class);
    Route::apiResource('client', ClientController::class);
    Route::apiResource('location', LocationController::class);
});
