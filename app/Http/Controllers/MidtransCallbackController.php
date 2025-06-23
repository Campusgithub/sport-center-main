<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MidtransCallbackController extends Controller
{
    public function handle(Request $request)
    {
        $notif = new \Midtrans\Notification();
        $transactionStatus = $notif->transaction_status;
        $orderId = $notif->order_id;

        // Update status_transaksi di database sesuai order_id
        DB::table('transactions')
            ->where('ticket_code', $orderId) // atau sesuaikan dengan kolom yang kamu pakai untuk order_id
            ->update(['status_transaksi' => $transactionStatus]);

        return response()->json(['message' => 'Callback processed']);
    }
}