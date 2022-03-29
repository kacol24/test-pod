<?php

use App\Http\Controllers\Api\ProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PrismController;

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

Route::middleware('auth:sanctum')
     ->group(function () {
         Route::apiResource('products', ProductController::class)
              ->only(['index']);
     });

Route::post('pickup', [PrismController::class, 'pickup'])->middleware('client');
Route::post('awb', [PrismController::class, 'awb'])->middleware('client');
Route::post('label', [PrismController::class, 'label'])->middleware('client');
