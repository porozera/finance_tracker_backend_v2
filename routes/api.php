<?php
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;


// Mendefinisikan rute API untuk pembayaran
Route::prefix('payments')->group(function () {
    Route::post('/', [PaymentController::class, 'create']);  // Create payment
    Route::get('/', [PaymentController::class, 'index']);  // List payments
    Route::put('/{id}', [PaymentController::class, 'update']);  // Update payment by ID
    Route::delete('/{id}', [PaymentController::class, 'delete']);  // Delete payment by ID
    Route::delete('/', [PaymentController::class, 'deleteAll']);  // Delete all payments
});

use App\Http\Controllers\CategoryController;

// Rute untuk kategori
Route::prefix('categories')->group(function () {
    Route::post('/', [CategoryController::class, 'create']); // Create new category
    Route::get('/', [CategoryController::class, 'index']); // Get all categories
    Route::get('/summary', [CategoryController::class, 'getSummary']); // Get categories with expense summary
    Route::put('/{id}', [CategoryController::class, 'update']); // Update category
    Route::delete('/{id}', [CategoryController::class, 'destroy']); // Delete category
    Route::delete('/', [CategoryController::class, 'destroyAll']); // Delete all categories
});

use App\Http\Controllers\AccountController;

// Rute untuk akun
Route::prefix('accounts')->group(function () {
    Route::post('/', [AccountController::class, 'create']); // Create new account
    Route::get('/', [AccountController::class, 'index']); // Get all accounts
    Route::get('/summary', [AccountController::class, 'getSummary']); // Get accounts with summary of income and expense
    Route::put('/{id}', [AccountController::class, 'update']); // Update account
    Route::delete('/{id}', [AccountController::class, 'destroy']); // Delete account
    Route::delete('/', [AccountController::class, 'destroyAll']); // Delete all accounts
});
