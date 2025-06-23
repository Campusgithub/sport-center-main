<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\VenueController;
use App\Http\Controllers\API\BookingController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\TransactionController;
use App\Http\Controllers\MidtransCallbackController;


// Public routes
Route::post('login', [AuthController::class, 'login']);

// ✅ BUAT MIDTRANS BOOKING ROUTE DI LUAR MIDDLEWARE
Route::post('/booking', [TransactionController::class, 'createMidtrans']);
Route::post('/midtrans-callback', [\App\Http\Controllers\MidtransCallbackController::class, 'handle']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('profile', [AuthController::class, 'profile']);

    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('venues', VenueController::class);

    Route::get('bookings', [BookingController::class, 'index']);
    Route::post('bookings', [BookingController::class, 'store']);
    Route::get('bookings/{id}', [BookingController::class, 'show']);
    Route::put('bookings/{id}', [BookingController::class, 'update']);
    Route::delete('bookings/{id}', [BookingController::class, 'destroy']);
    Route::get('/venues', [VenueController::class, 'index']);
});
