<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\VenueController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\AuthController;
use App\Models\Venue;
use Illuminate\Http\Request;
use App\Http\Controllers\PaymentController;
use App\Models\Booking;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\BookingControllerWeb;  // ✅ Sudah benar
use App\Http\Controllers\TransactionPrintController;
use Illuminate\Support\Facades\Log;
use App\Models\Transaction;

use App\Http\Controllers\API\BookingController as ApiBookingController;
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
    $booking = null;
    if ($request->has('code')) {
        $booking = Booking::where('ticket_code', $request->code)->first();
        if ($booking) {
            $booking = [
                'ticket_code' => $booking->ticket_code,
                'customer_name' => $booking->customer_name,
                'venue_name' => optional($booking->venue)->name, // gunakan optional() untuk menghindari error
                'date' => $booking->date,
                'time' => $booking->time,
                'status' => $booking->status,
                'price' => $booking->price,
                'customer_id' => $booking->customer_id,
            ];
        }
    }
    return Inertia::render('Booking', [
        'booking' => $booking,
    ]);
})->name('booking.form');

// Simpan Booking dari Form (POST)
Route::post('/booking', [BookingControllerWeb::class, 'store'])->name('booking.store');


// Cari booking berdasarkan kode
// Route::get('/booking-code', [BookingController::class, 'showByCode'])->name('booking.code');
Route::get('/track-booking/{ticketCode}', [ApiBookingController::class, 'trackBooking']);
Route::get('/track-order/{orderId}', [ApiBookingController::class, 'trackByOrderId']);

// Route::get('/track-order/{code}', [BookingController::class, 'trackByCodeOrOrder']); // DISABLED - use API instead

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

    // Route print transaksi admin == cetak struk transaksi
    Route::get('/admin/transactions/{transaction}/print', [\App\Http\Controllers\TransactionPrintController::class, 'print'])->name('transaction.print');
    // Jika ingin print transaksi user, gunakan controller yang sesuai dan pastikan tidak duplikat nama route
    // Contoh:
    // Route::get('/transactions/{id}/print', [\App\Http\Controllers\TransactionPrintController::class, 'print'])->name('transaction.user.print');
});


// =================== OPSIONAL =================== //
// Halaman Admin (Filament Panel) route send wa
Route::get('/booking/{id}/send-wa', [BookingControllerWeb::class, 'sendWa'])->name('booking.send-wa');
Route::get('/forgot-password', function () {
    return 'Forgot password page belum dibuat';
})->name('filament.password.request');


Route::get('/admin/auth/google/callback', [GoogleAuthController::class, 'callback']);

// =======Tambahkan route Midtrans di sini=======//
Route::post('/get-snap-token', [PaymentController::class, 'getSnapToken']);

// PINDAHKAN KE API.PHP - JANGAN DUPLIKAT
// Route::get('/api/booked-times', [App\Http\Controllers\API\BookedTimesController::class, 'index']);
// Route::get('/track-booking/{ticketCode}', [ApiBookingController::class, 'trackBooking']);
// Route::get('/track-order/{orderId}', [ApiBookingController::class, 'trackByOrderId']);

// Print transaction (ini tetap aktif)
Route::get('/transaction/{id}/print', [TransactionPrintController::class, 'print'])->name('transaction.print');

// Test route untuk cek booking
Route::get('/cek-booking/{ticket_code}', function($ticket_code) {
    Log::info('Cek Booking Request', ['ticket_code' => $ticket_code]);
    
    $booking = Transaction::where('ticket_code', $ticket_code)->first();
    
    if ($booking) {
        return response()->json([
            'found' => true,
            'data' => $booking
        ]);
    }
    
    return response()->json([
        'found' => false,
        'message' => 'Booking tidak ditemukan'
    ]);
});

// NONAKTIFKAN ROUTE BOOKING CONTROLLER BIASA UNTUK MENGHINDARI BENTROK
// Route::get('/track', [BookingController::class, 'showTrackingForm'])->name('booking.track');
// Route::post('/track', [BookingController::class, 'trackOrder'])->name('booking.track.submit');
