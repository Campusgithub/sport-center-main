<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class BookedTimesController extends Controller
{
    public function index(Request $request)
    {
        // Validasi input
        $request->validate([
            'venue_id' => 'required|integer|exists:venues,id',
            'date' => 'required|date_format:Y-m-d',
        ]);

        $venueId = $request->query('venue_id');
        $date = $request->query('date');
        
        Log::info('BookedTimes Request', [
            'venue_id' => $venueId,
            'date' => $date
        ]);

        if (!$venueId || !$date) {
            return response()->json(['bookedTimes' => []]);
        }

        $bookedTimes = [];

        // Metode 1: Dari transactions langsung (PRIORITAS UTAMA)
        try {
            $bookedFromTransactions = DB::table('transactions')
                ->where('venue_id', $venueId)
                ->whereDate('start_time', $date)
                ->whereIn('status_transaksi', ['Lunas', 'Pending', 'paid', 'pending'])
                ->where('isDeleted', 0) // pastikan tidak terhapus
                ->select('start_time')
                ->get()
                ->map(function($transaction) {
                    return Carbon::parse($transaction->start_time)->format('H:i');
                });

            $bookedTimes = $bookedFromTransactions->toArray();

            Log::info('Booked from transactions', [
                'count' => count($bookedTimes),
                'times' => $bookedTimes,
                'venue_id' => $venueId,
                'date' => $date
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching from transactions: ' . $e->getMessage());
        }

        // Metode 2: Dari transaction_slots (jika ada dan sebagai tambahan)
        try {
            $bookedFromSlots = DB::table('transaction_slots')
                ->join('transactions', 'transaction_slots.transaction_id', '=', 'transactions.id')
                ->where('transactions.venue_id', $venueId)
                ->whereDate('transaction_slots.start_time', $date)
                ->whereIn('transactions.status_transaksi', ['Lunas', 'Pending', 'paid', 'pending'])
                ->where('transactions.isDeleted', 0)
                ->select('transaction_slots.start_time')
                ->get()
                ->map(function($slot) {
                    return Carbon::parse($slot->start_time)->format('H:i');
                });

            // Gabungkan dengan hasil sebelumnya
            $bookedTimes = array_merge($bookedTimes, $bookedFromSlots->toArray());

            Log::info('Booked from transaction_slots', [
                'count' => $bookedFromSlots->count(),
                'times' => $bookedFromSlots->toArray()
            ]);

        } catch (\Exception $e) {
            Log::info('transaction_slots table not found or error: ' . $e->getMessage());
        }
        // Metode 3: Dari tabel bookings (jika ada sistem booking terpisah)
        try {
            $bookedFromBookings = DB::table('bookings')
                ->where('venue_id', $venueId)
                ->where('date', $date)
                ->whereIn('status', ['confirmed', 'pending', 'paid'])
                ->select('time')
                ->get()
                ->map(function($booking) {
                    return $booking->time; // asumsi format sudah H:i
                });

            // Gabungkan dengan hasil sebelumnya
            $bookedTimes = array_merge($bookedTimes, $bookedFromBookings->toArray());

        } catch (\Exception $e) {
            Log::info('bookings table not found: ' . $e->getMessage());
        }

        // Hapus duplikat dan urutkan
        $bookedTimes = array_unique($bookedTimes);
        sort($bookedTimes);

        Log::info('Final booked times', [
            'venue_id' => $venueId,
            'date' => $date,
            'total_booked' => count($bookedTimes),
            'times' => $bookedTimes
        ]);

        return response()->json([
            'success' => true,
            'bookedTimes' => $bookedTimes,
            'meta' => [
                'venue_id' => (int)$venueId,
                'date' => $date,
                'total_booked' => count($bookedTimes),
                'checked_at' => now()->toISOString()
            ]
        ]);
    }
}
