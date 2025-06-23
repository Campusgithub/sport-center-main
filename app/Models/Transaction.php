<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Transaction extends Model
{
    public $timestamps = false;
    
    protected $fillable = [
        'customer_id',
        'venue_id',
        'start_time',
        'end_time',
        'status_transaksi',
        'ticket_code',
        'ticket_url',
        'is_ticket_sent',
        'CompanyCode',
        'Status',
        'isDeleted',
        'CreatedBy',
        'CreatedDate',
        'LastUpdatedBy',
        'LastUpdatedDate'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'is_ticket_sent' => 'integer',
        'Status' => 'integer',
        'isDeleted' => 'integer',
        'CreatedDate' => 'datetime',
        'LastUpdatedDate' => 'datetime'
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }
} 