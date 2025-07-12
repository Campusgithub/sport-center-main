<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\VenueController;
use App\Http\Controllers\API\BookingController;
use App\Http\Controllers\API\BookedTimesController;
use App\Http\Controllers\API\TransactionController;
use App\Http\Controllers\MidtransCallbackController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('login', [AuthController::class, 'login']);
Route::post('/booking', [TransactionController::class, 'createMidtrans']);
Route::post('/midtrans-callback', [MidtransCallbackController::class, 'handle']);

// Public route untuk venues
Route::get('venues', [VenueController::class, 'index']);

// Endpoint untuk booked times (jam yang sudah dipesan)
Route::get('/booked-times', [BookedTimesController::class, 'index']);

// Track booking routes (public) - GUNAKAN API BOOKING CONTROLLER
Route::get('/booking/track/{ticketCode}', [BookingController::class, 'trackBooking']);
Route::get('/booking/track-by-order/{orderId}', [BookingController::class, 'trackByOrderId']);
Route::get('/track-order/{ticketCode}', [BookingController::class, 'trackByOrderId']);

// Public booking routes
Route::prefix('bookings')->group(function () {
    Route::get('/check-slots', [BookingController::class, 'checkAvailableSlots']);
    Route::post('/temporary', [BookingController::class, 'createTemporaryBooking']);
});

// Route untuk update status transaksi
Route::post('/transactions/update-status', [TransactionController::class, 'updateTransactionStatus']);
Route::patch('/transactions/{id}/approval-status', [TransactionController::class, 'updateApprovalStatus']);

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
    
    Route::post('/create-booking', [BookingController::class, 'createBooking']);
    Route::get('/booked-slots', [TransactionController::class, 'getBookedSlots']);
    Route::post('/temporary-booking', [TransactionController::class, 'createTemporaryBooking']);
});