<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    public $timestamps = false;
    
    protected $fillable = [
        'transaction_id',
        'payment_code',
        'payment_type',
        'status',
        'CompanyCode',
        'Status',
        'isDeleted',
        'CreatedBy',
        'CreatedDate',
        'LastUpdatedBy',
        'LastUpdatedDate'
    ];

    protected $casts = [
        'Status' => 'integer',
        'isDeleted' => 'integer',
        'CreatedDate' => 'datetime',
        'LastUpdatedDate' => 'datetime'
    ];

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }
} 