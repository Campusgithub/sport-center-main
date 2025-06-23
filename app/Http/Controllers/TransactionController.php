<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Midtrans\Snap;
use Midtrans\Config;
use App\Models\Transaction; // pastikan model ada
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

        Transaction::create([
            'venue_id' => $request->venue_id,
            'booking_date' => $request->date,
            'booking_time' => implode(',', $request->times),
            'name' => $request->name,
            'phone' => $request->phone,
            'total_price' => (int) $request->amount,
            'booking_code' => $orderId,
            'payment_method' => $request->payment_method,
            'status' => 'pending',
            'admin_status' => 'waiting',
        ]);

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $request->amount,
            ],
            'customer_details' => [
                'first_name' => $request->name,
                'phone' => $request->phone,
            ],
        ];

        $snapToken = Snap::getSnapToken($params);
        return response()->json(['snap_token' => $snapToken]);

    } catch (\Exception $e) {
        Log::error('Midtrans Error: ' . $e->getMessage());
        return response()->json(['error' => 'Midtrans gagal'], 500);
    }
}

}
