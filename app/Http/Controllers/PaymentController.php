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
            'amount' => 'required|numeric|min:1',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
        ]);

        \Midtrans\Config::$serverKey = config('services.midtrans.serverKey');
        \Midtrans\Config::$isProduction = config('services.midtrans.isProduction');
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        $order_id = 'ORDER-' . uniqid();
        $ticket_code = strtoupper(Str::random(10));

        // Selalu gunakan id guest (misal: 1)
        $guestId = 1; // pastikan id ini ada di tabel customers

        // Insert transaksi, customer_id selalu 0
        DB::table('transactions')->insert([
            'customer_id' => $guestId,
            'venue_id' => $request->venue_id,
            'start_time' => now(),
            'end_time' => now(),
            'status_transaksi' => 'pending',
            'ticket_code' => $ticket_code,
            'ticket_url' => '', // <-- ubah dari null menjadi string kosong
            'is_ticket_sent' => 0,
            'CompanyCode' => 'SPORT01',
            'Status' => 1,
            'isDeleted' => 0,
            'CreatedBy' => 'system',
            'CreatedDate' => now(),
            'LastUpdatedBy' => 'system',
            'LastUpdatedDate' => now(),
        ]);

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
