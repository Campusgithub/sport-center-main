<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'venue_id',
        'user_id',
        'date',
        'time',
        'status',
        'price'
    ];

    // Tambahkan enum untuk status
    const STATUS = [
        'PENDING' => 1,
        'VERIFIED' => 2,
        'TICKET_SENT' => 3,
        'IN_PROGRESS' => 4,
        'COMPLETED' => 5
    ];

    // Relasi dengan model lain
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }
} 