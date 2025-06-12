<?php 

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CarRent\CarController;

Route::middleware('auth')->prefix('cars')->group(function () {
    Route::get('/', [CarController::class, 'index']);
    Route::post('/', [CarController::class, 'store']);
    Route::get('/{car}', [CarController::class, 'show']);
    Route::put('/{car}', [CarController::class, 'update']);
    Route::delete('/{car}', [CarController::class, 'destroy']);
});