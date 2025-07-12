<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Transaction;
use Midtrans\Config;
use Midtrans\Notification;

class MidtransCallbackController extends Controller
{
    public function handle(Request $request)
    {
        try {
            // Log raw request untuk debugging
            Log::channel('midtrans')->info('Raw Midtrans Callback Request', [
                'method' => $request->method(),
                'all_data' => $request->all(),
                'headers' => $request->headers->all(),
                'ip' => $request->ip()
            ]);

            // Validasi request POST
            if ($request->method() !== 'POST') {
                Log::channel('midtrans')->warning('Invalid request method', [
                    'method' => $request->method()
                ]);
                return response()->json([
                    'status' => 'error',
                    'message' => 'Metode request tidak valid'
                ], 405);
            }

            // Set konfigurasi Midtrans
            Config::$serverKey = env('MIDTRANS_SERVER_KEY');
            Config::$isProduction = false;
            Config::$isSanitized = true;
            Config::$is3ds = true;

            // Buat instance notifikasi Midtrans
            $notif = new Notification();

            // Validasi input kritis
            if (!$notif->order_id || !$notif->transaction_status) {
                Log::channel('midtrans')->error('Invalid Midtrans notification', [
                    'order_id' => $notif->order_id,
                    'transaction_status' => $notif->transaction_status
                ]);
                return response()->json([
                    'status' => 'error',
                    'message' => 'Notifikasi Midtrans tidak valid'
                ], 400);
            }

            // Log detail notifikasi untuk debugging
            Log::channel('midtrans')->info('Midtrans Callback Received', [
                'order_id' => $notif->order_id,
                'transaction_status' => $notif->transaction_status,
                'payment_type' => $notif->payment_type,
                'gross_amount' => $notif->gross_amount
            ]);

            // Cari transaksi berdasarkan order_id
            $transaction = Transaction::where('ticket_code', $notif->order_id)->first();

            if (!$transaction) {
                Log::channel('midtrans')->warning('Transaksi tidak ditemukan', [
                    'order_id' => $notif->order_id
                ]);
                return response()->json([
                    'status' => 'error',
                    'message' => 'Transaksi tidak ditemukan'
                ], 404);
            }

            // Validasi signature key
            $signatureKey = hash('sha512', 
                $notif->order_id . 
                $notif->status_code . 
                $notif->gross_amount . 
                Config::$serverKey
            );

            if ($signatureKey !== $request->signature_key) {
                Log::channel('midtrans')->error('Signature key tidak valid', [
                    'received_signature' => $request->signature_key,
                    'calculated_signature' => $signatureKey
                ]);
                return response()->json([
                    'status' => 'error',
                    'message' => 'Signature key tidak valid'
                ], 400);
            }

            // Update status transaksi berdasarkan status Midtrans
            $originalStatus = $transaction->status_transaksi;
            switch ($notif->transaction_status) {
                case 'settlement':
                    $transaction->status_transaksi = 'paid';
                    break;
                case 'pending':
                    $transaction->status_transaksi = 'pending';
                    break;
                case 'deny':
                case 'expire':
                case 'cancel':
                    $transaction->status_transaksi = 'failed';
                    break;
                default:
                    Log::channel('midtrans')->warning('Status transaksi tidak dikenali', [
                        'status' => $notif->transaction_status
                    ]);
            }

            $transaction->save();

            Log::channel('midtrans')->info('Status transaksi diperbarui', [
                'order_id' => $transaction->ticket_code,
                'old_status' => $originalStatus,
                'new_status' => $transaction->status_transaksi
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Callback berhasil diproses'
            ]);

        } catch (\Exception $e) {
            Log::channel('midtrans')->error('Error processing Midtrans callback', [
                'error_message' => $e->getMessage(),
                'error_trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat memproses callback'
            ], 500);
        }
    }
}