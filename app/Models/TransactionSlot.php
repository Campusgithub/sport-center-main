<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionSlot extends Model
{
    protected $fillable = [
        'transaction_id',
        'start_time',
        'end_time',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
} 