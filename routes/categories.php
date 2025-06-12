<?php 

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CarRent\CategoryController;

Route::middleware('auth')->prefix('categories')->group(function () {
    Route::get('/', [CategoryController::class, 'index']);
    Route::post('/', [CategoryController::class, 'store']);
    Route::get('/{category}', [CategoryController::class, 'show']);
    Route::put('/{category}', [CategoryController::class, 'update']);
    Route::delete('/{category}', [CategoryController::class, 'destroy']);

});
