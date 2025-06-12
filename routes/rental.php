<?php 

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CarRent\RentalController;

Route::middleware('auth')->prefix('rentals')->group(function () {
    Route::post('/', [RentalController::class, 'store']);
    // Route::get('/{rental}', [RentalController::class, 'show']);
    // Route::put('/{rental}', [RentalController::class, 'update']);
    // Route::delete('/{rental}', [RentalController::class, 'destroy']);
});