<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\VenueController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\AuthController;
use App\Models\Venue;
use Illuminate\Http\Request;
use App\Http\Controllers\PaymentController;

// =================== PUBLIC ROUTES =================== //

// Halaman Utama
Route::get('/', function () {
    $venues = Venue::all();
    return Inertia::render('Home', [
        'venues' => $venues
    ]);
})->name('home');

// Halaman Daftar Venue
Route::get('/venue', [VenueController::class, 'index'])->name('venue');

// Detail Venue
Route::get('/venue/{id}', [VenueController::class, 'show'])->name('venue.detail');

// Halaman Booking (tampil form setelah klik "Pesan Sekarang")
Route::get('/booking', function (Request $request) {
    if (!$request->has(['venue_id', 'date', 'times'])) {
        return Inertia::render('Booking', [
            'bookings' => [],
            'error' => 'Data booking tidak lengkap'
        ]);
    }

    $venue = Venue::findOrFail($request->venue_id);
    return Inertia::render('Booking', [
        'venue' => $venue,
        'selectedDate' => $request->date,
        'selectedTimes' => explode(',', $request->times)
    ]);
})->name('booking.form');

// Simpan Booking dari Form (POST)
Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');

// Cari booking berdasarkan kode
Route::get('/booking-code', [BookingController::class, 'showByCode'])->name('booking.code');

// Halaman Kontak & Login User (frontend user biasa)
Route::get('/contact', fn() => Inertia::render('Contact'))->name('contact');
Route::get('/login', fn() => Inertia::render('Login'))->name('login');

Route::get('/booking/success', function () {
    return view('booking.success');
});

// =================== AUTH ADMIN =================== //

// Login Manual Admin
Route::get('/admin/login', function () {
    return view('filament.pages.auth.custom-login');
})->name('filament.admin.auth.login');

Route::post('/admin/login', [AuthController::class, 'login']);

// Login with Google (ADMIN)
Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('auth.google.redirect');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback']);

// =================== ADMIN ROUTES (Filament Panel) =================== //

Route::middleware(['auth'])->group(function () {
    // Kalo kamu pakai dashboard sendiri pakai React/Inertia, kamu bisa aktifkan ini:
    // Route::get('/admin/dashboard', fn() => Inertia::render('pages/Dashboard'))->name('admin.dashboard');

    Route::get('/admin/bookings', [BookingController::class, 'index'])->name('admin.booking');
});


// =================== OPSIONAL =================== //

Route::get('/forgot-password', function () {
    return 'Forgot password page belum dibuat';
})->name('filament.password.request');


Route::get('/admin/auth/google/callback', [GoogleAuthController::class, 'callback']);

// =======Tambahkan route Midtrans di sini=======//
Route::post('/get-snap-token', [PaymentController::class, 'getSnapToken']);
Route::post('/midtrans-callback', [\App\Http\Controllers\MidtransCallbackController::class, 'handle']);