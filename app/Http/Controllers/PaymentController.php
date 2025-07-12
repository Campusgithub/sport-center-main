<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function getSnapToken(Request $request)
    {
        // Validasi input (tanpa customer_id)
        $validated = $request->validate([
            'venue_id' => 'required|integer',
            'amount' => 'required|integer|min:1',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
        ]);

        \Midtrans\Config::$serverKey = config('services.midtrans.serverKey');
        \Midtrans\Config::$isProduction = config('services.midtrans.isProduction');
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        $order_id = 'ORDER-' . uniqid();
        $ticket_code = strtoupper(Str::random(10));

        // SELALU INSERT CUSTOMER BARU
        $customerId = DB::table('customers')->insertGetId([
            'name' => $request->name,
            'phone_number' => $request->phone,
            'email' => 'user@email.com',
            'CompanyCode' => 'SPORT01',
            'Status' => 1,
            'isDeleted' => 0,
            'CreatedBy' => 'system',
            'CreatedDate' => now(),
            'LastUpdatedBy' => 'system',
            'LastUpdatedDate' => now(),
        ]);

        // Ambil slot paling awal dan akhir dari times
        $start_time = now();
        $end_time = now();
        if (is_array($request->times) && count($request->times) > 0) {
            $sorted = $request->times;
            sort($sorted);
            [$startSlot] = explode('-', $sorted[0]);
            [, $endSlot] = explode('-', $sorted[count($sorted)-1]);
            $date = $request->date;
            $start_time = $date . ' ' . $startSlot . ':00';
            $end_time = $date . ' ' . $endSlot . ':00';
        }

        // Insert transaksi
        $transactionId = DB::table('transactions')->insertGetId([
            'customer_id' => $customerId,
            'venue_id' => $request->venue_id,
            'start_time' => $start_time,
            'end_time' => $end_time,
            'status_transaksi' => 'pending',
            'ticket_code' => $ticket_code,
            'ticket_url' => '',
            'is_ticket_sent' => 0,
            'CompanyCode' => 'SPORT01',
            'Status' => 1,
            'isDeleted' => 0,
            'CreatedBy' => 'system',
            'CreatedDate' => now(),
            'LastUpdatedBy' => 'system',
            'LastUpdatedDate' => now(),
            'amount' => $request->amount,
            'order_id' => $order_id,
        ]);

        // Simpan semua slot booking ke transaction_slots
        if (is_array($request->times)) {
            foreach ($request->times as $slot) {
                [$start, $end] = explode('-', $slot);
                $date = $request->date;
                $start_time = $date . ' ' . $start . ':00';
                $end_time = $date . ' ' . $end . ':00';
                DB::table('transaction_slots')->insert([
                    'transaction_id' => $transactionId,
                    'start_time' => $start_time,
                    'end_time' => $end_time,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $params = [
            'transaction_details' => [
                'order_id' => $order_id,
                'gross_amount' => $request->amount,
            ],
            'customer_details' => [
                'first_name' => $request->name,
                'email' => 'user@email.com',
                'phone' => $request->phone,
            ],
        ];

        $snapToken = \Midtrans\Snap::getSnapToken($params);

        return response()->json(['token' => $snapToken]);
    }
}
