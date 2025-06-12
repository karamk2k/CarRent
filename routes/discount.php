<?php 
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CarRent\DiscountController;

Route::group(['middleware' => ['auth']], function () {
    Route::get('/discount', [DiscountController::class, 'index']);
    Route::post('/discount',[DiscountController::class, 'store']);
    Route::get('/discount/{discount}', [DiscountController::class, 'show']);
    Route::put('/discount/{discount}', [DiscountController::class, 'update']);
    Route::delete('/discount/{discount}', [DiscountController::class, 'destroy']);
});