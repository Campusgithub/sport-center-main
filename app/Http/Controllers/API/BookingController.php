<?php

namespace App\Http\Controllers\API;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Transaction;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\Venue;
use App\Models\TransactionSlot;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['venue', 'user'])->latest()->get();
        
        return response()->json([
            'success' => true,
            'data' => $bookings
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'venue_id' => 'required|exists:venues,id',
            'date' => 'required|date',
            'time' => 'required',
            'status' => 'required|in:pending,confirmed,completed'
        ]);

        $booking = Booking::create([
            ...$validated,
            'user_id' => auth()->id
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Booking created successfully',
            'data' => $booking
        ], 201);
    }

    public function show($id)
    {
        $booking = Booking::with(['venue', 'user'])->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'data' => $booking
        ]);
    }

    public function update(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,completed'
        ]);

        $booking->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Booking updated successfully',
            'data' => $booking
        ]);
    }

    public function destroy($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->delete();

        return response()->json([
            'success' => true,
            'message' => 'Booking deleted successfully'
        ]);
    }

    public function statusByCode(Request $request)
    {
        $code = $request->query('code');
        $booking = Transaction::with(['customer', 'venue', 'slots'])
            ->where('ticket_code', $code)
            ->first();

        if (!$booking) {
            return response()->json(['error' => 'Kode booking tidak ditemukan'], 404);
        }

        // Mapping status ke step
        $statusMap = [
            'pending' => 'pending',
            'paid' => 'paid',
            'playing' => 'playing',
            'done' => 'done',
        ];
        $step = $statusMap[$booking->status_transaksi] ?? 'pending';

        return response()->json([
            'nama' => $booking->customer->name,
            'venue' => $booking->venue->name,
            'tanggal' => $booking->start_time->format('Y-m-d'),
            'jam' => $booking->slots->count() > 0
                ? $booking->slots->min('start_time')->format('H:i') . '—' . $booking->slots->max('end_time')->format('H:i')
                : $booking->start_time->format('H:i') . '—' . $booking->end_time->format('H:i'),
            'total' => $booking->amount,
            'status' => $step,
        ]);
    }

    public function trackBooking($ticketCode)
    {
        // Log untuk debugging
        Log::info('Tracking Booking Request', [
            'ticket_code' => $ticketCode,
            'full_url' => request()->fullUrl(),
            'method' => request()->method()
        ]);

        // Validasi input
        if (empty($ticketCode)) {
            Log::warning('Empty Ticket Code');
            return response()->json([
                'message' => 'Kode booking tidak boleh kosong'
            ], 400);
        }

        // Cari transaksi berdasarkan ticket_code
        $booking = Transaction::where('ticket_code', $ticketCode)
            ->with(['customer', 'venue'])
            ->first();

        // Log tambahan untuk debugging
        Log::info('Booking Search Result', [
            'ticket_code' => $ticketCode,
            'booking_found' => $booking ? 'Yes' : 'No'
        ]);

        // Jika booking tidak ditemukan
        if (!$booking) {
            Log::warning('Booking Not Found', [
                'searched_ticket_code' => $ticketCode
            ]);

            return response()->json([
                'message' => 'Kode booking tidak ditemukan'
            ], 404);
        }

        // Mapping status booking
        $statusMapping = [
            'pending' => [
                'code' => 1, 
                'label' => 'Menunggu Pembayaran',
                'color' => 'yellow'
            ],
            'paid' => [
                'code' => 2, 
                'label' => 'Lunas',
                'color' => 'green'
            ],
            'completed' => [
                'code' => 3, 
                'label' => 'Selesai',
                'color' => 'blue'
            ]
        ];

        // Ambil status mapping
        $status = $statusMapping[$booking->status_transaksi] ?? [
            'code' => 0, 
            'label' => 'Status Tidak Dikenal',
            'color' => 'gray'
        ];

        return response()->json([
            'ticket_code' => $booking->ticket_code,
            'customer_name' => $booking->customer->name,
            'venue_name' => $booking->venue->name,
            'booking_date' => $booking->start_time->format('d F Y'),
            'booking_time' => $booking->start_time->format('H:i') . ' - ' . $booking->end_time->format('H:i'),
            'status_code' => $status['code'],
            'status_label' => $status['label'],
            'total_price' => formatRupiah($booking->amount)
        ]);
    }

    public function trackByCodeOrOrder($code)
    {
        // Cek apakah input terlihat seperti order_id (misal: ORDER-...)
        if (str_starts_with($code, 'ORDER-')) {
            return $this->trackByOrderId($code);
        }
    
        // Jika tidak, anggap sebagai ticket_code
        return $this->trackBooking($code);
    }
    
    


    public function checkAvailableSlots(Request $request)
    {
        Log::info('Check Available Slots Request', [
            'venue_id' => $request->input('venue_id'),
            'date' => $request->input('date')
        ]);

        $request->validate([
            'venue_id' => 'required|exists:venues,id',
            'date' => 'required|date_format:Y-m-d',
        ]);

        $venueId = $request->input('venue_id');
        $date = $request->input('date');

        // Ambil venue untuk mendapatkan jam operasional
        $venue = Venue::findOrFail($venueId);
        
        // Tentukan jam operasional venue
        $openTime = Carbon::parse($venue->open_time ?? '07:00');
        $closeTime = Carbon::parse($venue->close_time ?? '23:00');
        
        // Generate semua slot waktu
        $slots = [];
        $currentTime = $openTime->copy();
        
        while ($currentTime->lt($closeTime)) {
            $slotStart = $currentTime->format('H:i');
            $slotEnd = $currentTime->addHour()->format('H:i');
            
            // Log detail booking untuk setiap slot
            $this->logBookingDetails($venueId, $date, $slotStart);

            // Cek apakah slot sudah dibooking di Booking
            $isBookedInBooking = Booking::where('venue_id', $venueId)
                ->where('date', $date)
                ->where('time', $slotStart)
                ->whereIn('status', [
                    Booking::STATUS['PENDING'], 
                    Booking::STATUS['VERIFIED'], 
                    Booking::STATUS['IN_PROGRESS']
                ])
                ->exists();

            // Cek apakah slot sudah dibooking di Transaction
            $isBookedInTransaction = Transaction::where('venue_id', $venueId)
                ->whereDate('start_time', $date)
                ->where(function($query) use ($slotStart, $slotEnd) {
                    $query->whereBetween('start_time', [$slotStart, $slotEnd])
                          ->orWhereBetween('end_time', [$slotStart, $slotEnd]);
                })
                ->whereIn('status_transaksi', ['pending', 'paid', 'settlement'])
                ->exists();
            
            $slots[] = [
                'start_time' => $slotStart,
                'end_time' => $slotEnd,
                'is_available' => !($isBookedInBooking || $isBookedInTransaction)
            ];
        }

        return response()->json([
            'venue_id' => $venueId,
            'date' => $date,
            'slots' => $slots
        ]);
    }

    public function createTemporaryBooking(Request $request)
    {
        Log::info('Temporary Booking Request', [
            'venue_id' => $request->input('venue_id'),
            'date' => $request->input('date'),
            'time' => $request->input('time'),
            'name' => $request->input('name'),
            'phone' => $request->input('phone')
        ]);

        $request->validate([
            'venue_id' => 'required|exists:venues,id',
            'date' => 'required|date_format:Y-m-d',
            'time' => 'required|date_format:H:i',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
        ]);

        // Log detail booking sebelum validasi
        $this->logBookingDetails(
            $request->input('venue_id'), 
            $request->input('date'), 
            $request->input('time')
        );

        // Cek apakah slot sudah dibooking di Booking
        $existingBookingBooking = Booking::where('venue_id', $request->venue_id)
            ->where('date', $request->date)
            ->where('time', $request->time)
            ->whereIn('status', [
                Booking::STATUS['PENDING'], 
                Booking::STATUS['VERIFIED'], 
                Booking::STATUS['IN_PROGRESS']
            ])
            ->first();

        // Cek apakah slot sudah dibooking di Transaction
        $existingBookingTransaction = Transaction::where('venue_id', $request->venue_id)
            ->whereDate('start_time', $request->date)
            ->where(function($query) use ($request) {
                $startTime = Carbon::parse($request->time);
                $endTime = $startTime->copy()->addHour();
                
                $query->whereBetween('start_time', [$request->time, $endTime])
                      ->orWhereBetween('end_time', [$request->time, $endTime]);
            })
            ->whereIn('status_transaksi', ['pending', 'paid', 'settlement'])
            ->first();

        if ($existingBookingBooking || $existingBookingTransaction) {
            Log::warning('Slot Already Booked', [
                'venue_id' => $request->venue_id,
                'date' => $request->date,
                'time' => $request->time,
                'booking_in_booking' => $existingBookingBooking ? 'Yes' : 'No',
                'booking_in_transaction' => $existingBookingTransaction ? 'Yes' : 'No'
            ]);

            return response()->json([
                'message' => 'Slot sudah dibooking atau sudah lunas'
            ], 400);
        }

        // Ambil harga venue
        $venue = Venue::findOrFail($request->venue_id);

        // Buat booking sementara
        $booking = Booking::create([
            'venue_id' => $request->venue_id,
            'date' => $request->date,
            'time' => $request->time,
            'status' => Booking::STATUS['PENDING'],
            'customer_name' => $request->name,
            'customer_phone' => $request->phone,
            'price' => $venue->price
        ]);

        Log::info('Temporary Booking Created', [
            'booking_id' => $booking->id,
            'venue_id' => $booking->venue_id,
            'date' => $booking->date,
            'time' => $booking->time
        ]);

        return response()->json([
            'message' => 'Booking sementara berhasil',
            'booking_id' => $booking->id
        ], 201);
    }

    public function createBooking(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'venue_id' => 'required|exists:venues,id',
            'date' => 'required|date',
            'time_slot' => 'required|string',
            'user_id' => 'required|exists:users,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Cek ketersediaan slot
        $existingBooking = Transaction::where('venue_id', $request->venue_id)
            ->whereDate('start_time', $request->date)
            ->where('status_transaksi', ['pending', 'paid'])
            ->whereHas('slots', function ($query) use ($request) {
                $query->where('start_time', $request->time_slot);
            })
            ->exists();

        if ($existingBooking) {
            return response()->json([
                'success' => false,
                'message' => 'Slot waktu sudah dibooking'
            ], 400);
        }

        // Buat transaksi baru
        $transaction = Transaction::create([
            'venue_id' => $request->venue_id,
            'customer_id' => $request->user_id,
            'start_time' => Carbon::parse($request->date . ' ' . explode('-', $request->time_slot)[0]),
            'end_time' => Carbon::parse($request->date . ' ' . explode('-', $request->time_slot)[1]),
            'status_transaksi' => 'pending',
            'amount' => Venue::findOrFail($request->venue_id)->price
        ]);

        // Buat slot transaksi
        TransactionSlot::create([
            'transaction_id' => $transaction->id,
            'start_time' => $request->time_slot,
            'end_time' => $request->time_slot
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Booking berhasil dibuat',
            'data' => $transaction
        ], 201);
    }

    // Tambahkan method baru untuk logging detail booking
    private function logBookingDetails($venueId, $date, $time)
    {
        // Log detail booking untuk debugging
        $bookingInBooking = Booking::where('venue_id', $venueId)
            ->where('date', $date)
            ->where('time', $time)
            ->get();

        $bookingInTransaction = Transaction::where('venue_id', $venueId)
            ->whereDate('start_time', $date)
            ->where(function($query) use ($time) {
                $startTime = Carbon::parse($time);
                $endTime = $startTime->copy()->addHour();
                
                $query->whereBetween('start_time', [$time, $endTime])
                       ->orWhereBetween('end_time', [$time, $endTime]);
            })
            ->get();

        Log::info('Booking Details Check', [
            'venue_id' => $venueId,
            'date' => $date,
            'time' => $time,
            'bookings_in_booking_table' => $bookingInBooking->toArray(),
            'bookings_in_transaction_table' => $bookingInTransaction->toArray()
        ]);
    }

    public function trackByOrderId($orderId)
    {
        Log::info('Tracking by Order ID', [
            'order_id' => $orderId
        ]);

        // Cari transaksi berdasarkan order_id
        $booking = Transaction::where('order_id', $orderId)
            ->with(['customer', 'venue'])
            ->first();

        if (!$booking) {
            return response()->json([
                'message' => 'Order ID tidak ditemukan'
            ], 404);
        }

        // Mapping status booking
        $statusMapping = [
            'pending' => [
                'code' => 1, 
                'label' => 'Menunggu Pembayaran',
                'color' => 'yellow'
            ],
            'paid' => [
                'code' => 2, 
                'label' => 'Lunas',
                'color' => 'green'
            ],
            'completed' => [
                'code' => 3, 
                'label' => 'Selesai',
                'color' => 'blue'
            ]
        ];

        $status = $statusMapping[$booking->status_transaksi] ?? [
            'code' => 0, 
            'label' => 'Status Tidak Dikenal',
            'color' => 'gray'
        ];

        return response()->json([
            'order_id' => $booking->order_id,
            'ticket_code' => $booking->ticket_code,
            'customer_name' => $booking->customer->name,
            'venue_name' => $booking->venue->name,
            'booking_date' => $booking->start_time->format('d F Y'),
            'booking_time' => $booking->start_time->format('H:i') . ' - ' . $booking->end_time->format('H:i'),
            'status_code' => $status['code'],
            'status_label' => $status['label'],
            'total_price' => formatRupiah($booking->amount)
        ]);
    }
}