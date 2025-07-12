<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionPrintController extends Controller
{
    public function print($id)
    {
        $transaction = Transaction::findOrFail($id);
        return view('transactions.print', compact('transaction'));
    }
}