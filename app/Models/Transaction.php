<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'order_id',
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
        'LastUpdatedDate',
        'updated_at', // Tambahkan kolom baru
        'approval_status',
    ];

    //protected $dates = ['start_time', 'end_time'];
    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];
    
    // Nonaktifkan timestamps default Laravel jika tidak diperlukan
    public $timestamps = false;

    // Relasi dengan Customer
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    // Relasi dengan Venue
    public function venue()
    {
        return $this->belongsTo(Venue::class, 'venue_id');
    }

    // Relasi dengan Transaction Slots (jika ada)
    public function slots()
    {
        return $this->hasMany(TransactionSlot::class);
    }

    // Method baru untuk tracking booking (tambahkan di sini)
    public function getBookingTrackingDetails()
    {
        // Mapping status booking
        $statusMapping = [
            'pending' => [
                'code' => 1, 
                'label' => 'Menunggu Pembayaran',
                'color' => 'yellow'
            ],
            'paid' => [
                'code' => 2, 
                'label' => 'Lunas',
                'color' => 'green'
            ],
            'completed' => [
                'code' => 3, 
                'label' => 'Selesai',
                'color' => 'blue'
            ]
        ];

        // Ambil status mapping
        $status = $statusMapping[$this->status_transaksi] ?? [
            'code' => 0, 
            'label' => 'Status Tidak Dikenal',
            'color' => 'gray'
        ];

        return [
            'ticket_code' => $this->ticket_code,
            'customer_name' => $this->customer->name,
            'venue_name' => $this->venue->name,
            'booking_date' => $this->start_time->format('d F Y'),
            'booking_time' => $this->start_time->format('H:i') . ' - ' . $this->end_time->format('H:i'),
            'status_code' => $status['code'],
            'status_label' => $status['label'],
            'total_price' => $this->amount
        ];
    }
}