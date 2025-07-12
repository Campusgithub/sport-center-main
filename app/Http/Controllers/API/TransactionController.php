<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Midtrans\Snap;
use Midtrans\Config;
use App\Models\Transaction;
use App\Models\Customer;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
    public function createMidtrans(Request $request)
    {
        try {
            Config::$serverKey = env('MIDTRANS_SERVER_KEY');
            Config::$isProduction = false;
            Config::$isSanitized = true;
            Config::$is3ds = true;

            $orderId = 'BOOK-' . strtoupper(substr(uniqid(), 0, 8));

            // Ambil data dari request
            $bookingDate = $request->date; // contoh: 2025-06-24
            $bookingTime = $request->time; // contoh: 08:00-09:00
            $name        = $request->name;
            $name        = $request->name;
            $phone       = normalizePhone($request->phone); // ✅ Tambahkan ini
            Log::info('Nomor sebelum dan sesudah normalisasi', [
            'original' => $request->phone,
            'normalized' => $phone,
            ]);

            $totalPrice  = $request->total_price;
            $amount      = $request->amount; // pastikan ini ada dan tidak null

            // Cari customer berdasarkan nama dan nomor telepon, jika tidak ada baru buat
            $customer = Customer::where('name', $name)
                ->where('phone_number', $phone)
                ->first();
            if (!$customer) {
                $customer = Customer::create([
                    'phone_number' => $phone,
                    'name' => $name,
                    'email' => null,
                    'CompanyCode' => 'SPORT01',
                    'Status' => 1,
                    'isDeleted' => 0,
                    'CreatedBy' => 'system',
                    'CreatedDate' => now(),
                    'LastUpdatedBy' => 'system',
                    'LastUpdatedDate' => now(),
                ]);
            }

            // Pisahkan jam mulai dan selesai
            $times = $request->times; // ini harus array!
            if (is_array($times) && count($times) > 0) {
            $firstSlot = explode('-', $times[0])[0];
            $lastSlot = explode('-', $times[count($times) - 1])[1];
            $bookingDate = $request->date;
            $start_time = $bookingDate . ' ' . $firstSlot . ':00';
            $end_time = $bookingDate . ' ' . $lastSlot . ':00';
            $times_slot = implode(', ', $times); // simpan ke kolom khusus untuk dicetak tiket
        } else {
            // fallback atau error
            $start_time = null;
            $end_time = null;
            $times_slot='';
}

            // Gabungkan dengan tanggal booking
            $start_time = $bookingDate . ' ' . $start . ':00';
            $end_time   = $bookingDate . ' ' . $end . ':00';

            // Simpan transaksi
            Transaction::create([
                'venue_id'      => $request->venue_id,
                'customer_id'   => $customer->id,
                'start_time'    => $start_time,
                'end_time'      => $end_time,
                'amount'        => $amount,
                'status_transaksi' => 'pending',
                // kolom lain sesuai kebutuhan
            ]);

            $params = [
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => (int) $amount,
                ],
                'customer_details' => [
                    'first_name' => $name,
                    'phone' => $phone,
                ],
            ];

            $snapToken = Snap::getSnapToken($params);
            return response()->json(['snap_token' => $snapToken]);

        } catch (\Exception $e) {
            Log::error('Midtrans Error: ' . $e->getMessage());
            return response()->json(['error' => 'Midtrans gagal'], 500);
        }
    }

    public function print($id)
    {
        // Logic untuk mencetak transaksi
    }
}
