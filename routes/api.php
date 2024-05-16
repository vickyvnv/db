<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DBI\CategoryController;
use App\Http\Controllers\DBI\PriorityController;
use App\Http\Controllers\DBI\MarketController;
use App\Http\Controllers\DBI\DbiTypeController;

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

Route::apiResource('categories', CategoryController::class);

Route::resource('priorities', PriorityController::class);

Route::resource('markets', MarketController::class);

Route::resource('dbiTypes', DbiTypeController::class);


