<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CarRent\DiscountController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CarController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\UserBanController;
use App\Http\Controllers\Admin\RentalController;
Route::middleware(['auth', 'admin'])->group(function () {
    // Dashboard view
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    // Dashboard API endpoints
    Route::get('/admin/stats', [DashboardController::class, 'stats'])->name('admin.stats');
    Route::get('/admin/activities', [DashboardController::class, 'activities'])->name('admin.activities');

    // Categories Management
    Route::get('/admin/categories', [CategoryController::class, 'index'])->name('admin.categories.index');
    Route::post('/admin/categories', [CategoryController::class, 'store'])->name('admin.categories.store');
    Route::get('/admin/categories/{category}/edit', [CategoryController::class, 'edit'])->name('admin.categories.edit');
    Route::put('/admin/categories/{category}', [CategoryController::class, 'update'])->name('admin.categories.update');
    Route::delete('/admin/categories/{category}', [CategoryController::class, 'destroy'])->name('admin.categories.destroy');

    // Cars Management
    Route::get('/admin/cars', [CarController::class, 'index'])->name('admin.cars.index');
    Route::post('/admin/cars', [CarController::class, 'store'])->name('admin.cars.store');
    Route::get('/admin/cars/{car}/edit', [CarController::class, 'edit'])->name('admin.cars.edit');
    Route::put('/admin/cars/{car}', [CarController::class, 'update'])->name('admin.cars.update');
    Route::delete('/admin/cars/{car}', [CarController::class, 'destroy'])->name('admin.cars.destroy');

    // Discounts Management
    Route::get('/admin/discounts', [\App\Http\Controllers\Admin\DiscountController::class, 'index'])->name('admin.discounts.index');
    Route::post('/admin/discounts', [\App\Http\Controllers\Admin\DiscountController::class, 'store'])->name('admin.discounts.store');
    Route::get('/admin/discounts/get', [\App\Http\Controllers\Admin\DiscountController::class, 'getDiscounts'])->name('admin.discounts.get');
    Route::delete('/admin/discounts/{discount}', [\App\Http\Controllers\Admin\DiscountController::class, 'destroy'])->name('admin.discounts.destroy');

    // Users Management
    Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');

    // User Ban Management
    Route::get('/admin/users/ban', [UserBanController::class, 'index'])->name('admin.users.ban.index');
    Route::get('/admin/users/banned', [UserBanController::class, 'banned'])->name('admin.users.banned');
    Route::post('/admin/users/{user}/ban', [UserBanController::class, 'ban'])->name('admin.users.ban');
    Route::post('/admin/users/{user}/unban', [UserBanController::class, 'unban'])->name('admin.users.unban');
    Route::put('/admin/users/{user}/role', [UserController::class, 'updateRole'])->name('admin.users.update-role');

    // Rentals Management
    Route::get('/admin/rentals', [RentalController::class, 'index'])->name('admin.rentals.index');
    Route::put('/admin/rentals/{rental}/confirmation', [RentalController::class, 'updateConfirmation'])->name('admin.rentals.update-confirmation');
    Route::put('/admin/rentals/{rental}/cancellation', [RentalController::class, 'updateCancellation'])->name('admin.rentals.update-cancellation');
    Route::put('/admin/rentals/{rental}/completed', [RentalController::class, 'updateCompleted'])->name('admin.rentals.update-completed');
});
