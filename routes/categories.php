<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CarRent\CategoryController;
use App\Http\Controllers\Api\CarRent\CarController;

Route::middleware('auth')->prefix('categories')->group(function () {
    Route::get('/', [CategoryController::class, 'index']);

    Route::get('/{category}', [CategoryController::class, 'show']);
    Route::post('/', [CarController::class, 'store']);
    Route::put('/{car}', [CarController::class, 'update']);
    Route::delete('/{car}', [CarController::class, 'destroy']);

});
