<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    public $timestamps = false;
    
    protected $fillable = [
        'name',
        'phone_number',
        'email',
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

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
} 