<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Venue extends Model
{
    use HasFactory;

    // Aktifkan created_at dan updated_at
    public $timestamps = true;

    // Field yang boleh diisi
    protected $fillable = [
        'name',
        'type',
        'location',
        'image',
        'price',
        'image_url',
        'facilities',
        'created_by',
        'updated_by'
    ];


    // Cast tipe data
    protected $casts = [
        'facilities' => 'array',
        'price' => 'decimal:2'
    ];

    // Relasi ke kategori (jika digunakan)
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    // Relasi ke transaksi
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    // Relasi ke booking
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    // Isi otomatis created_by
    protected static function booted()
    {
        static::creating(function ($venue) {
            $venue->created_by = 'admin';
        });
    }
}
