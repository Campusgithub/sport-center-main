<?php


namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'venue_id' => 'required|exists:venues,id',
            'name' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'date' => 'required|date',
            'time_slots' => 'required|array|min:1',
            'total_price' => 'required|numeric',
        ]);

        $bookingCode = 'BOOK-' . strtoupper(substr(uniqid(), 0, 8));

        Transaction::create([
            'venue_id' => $data['venue_id'],
            'name' => $data['name'],
            'phone' => $data['phone'],
            'booking_date' => $data['date'],
            'booking_time' => implode(', ', $data['time_slots']),
            'total_price' => $data['total_price'],
            'status' => 'pending',
            'booking_code' => $bookingCode,
        ]);

        return view('booking.checkout', [
    'bookingCode' => $bookingCode,
]);

    }
}
