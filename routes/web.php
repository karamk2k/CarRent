<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CarRent\CarController;
use App\Http\Controllers\Api\CarRent\RentalController;
use App\Http\Controllers\Api\CarRent\DiscountController;
use App\Http\Controllers\UserHistoryController;

// Public routes



// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');

    Route::get('/register', function () {
        return view('auth.register');
    })->name('register');

    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
    Route::post('/register', [AuthController::class, 'store']);
});

// Protected routes
Route::middleware(['auth','isUserBanned'])->group(function () {

    Route::get('/', function(){
        return view('home');
    })->name('home');


    Route::get('/cars', [CarController::class, 'index'])->name('cars.index');
    Route::get('/cars/{car}', [CarController::class, 'show'])->name('cars.show');
    // Auth actions
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/send-otp', [AuthController::class, 'send_otp'])->name('auth.send-otp');
    Route::post('/verify-otp', [AuthController::class, 'verify_otp'])->name('auth.verify-otp');


    // Favorites management
    Route::get('/favorites', [AuthController::class, 'get_favorites'])->name('favorites.index');
    Route::post('/favorites/add', [AuthController::class, 'add_favorite'])->name('favorites.add');
    Route::delete('/favorites/remove', [AuthController::class, 'remove_favorite'])->name('favorites.remove');

    // Rentals
    Route::post('/rentals', [RentalController::class, 'store'])->name('rentals.store');
    Route::get('/rentals', [RentalController::class, 'index'])->name('rentals.index');
    Route::get('/rentals/{rental}', [RentalController::class, 'show'])->name('rentals.show');
    Route::post('/rentals/{rental}/confirm-payment', [RentalController::class, 'confirmPayment'])->name('rentals.confirm-payment');
    Route::post('/rentals/{rental}/cancel', [RentalController::class, 'cancelRental'])->name('rentals.cancel');
    Route::get('/rentals/check-status', [RentalController::class, 'checkStatus'])->name('rentals.check-status');

    // Profile
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
    Route::put('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');

    // User history
    Route::get('/my-history', [UserHistoryController::class, 'index'])->name('user.history');
    Route::get('/my-favorites', function(){
        return view('favorites.index');
    })->name('favorites.index.page');
    // Discounts
    Route::get('/discounts/check/{code}', [DiscountController::class, 'check'])->name('discounts.check');
});



require __DIR__.'/categories.php';

require __DIR__.'/admin.php';