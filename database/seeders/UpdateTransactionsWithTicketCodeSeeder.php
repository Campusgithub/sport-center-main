<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transaction;
use Illuminate\Support\Str;

class UpdateTransactionsWithTicketCodeSeeder extends Seeder
{
    public function run()
    {
        $transactions = Transaction::whereNull('ticket_code')->get();
        
        foreach ($transactions as $transaction) {
            // Generate unique ticket code
            $ticketCode = 'ORDER-' . strtoupper(Str::random(10));
            
            // Pastikan kode unik
            while (Transaction::where('ticket_code', $ticketCode)->exists()) {
                $ticketCode = 'ORDER-' . strtoupper(Str::random(10));
            }
            
            $transaction->ticket_code = $ticketCode;
            $transaction->save();
        }
    }
}
