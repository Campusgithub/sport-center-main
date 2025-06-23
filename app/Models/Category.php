<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    public $timestamps = false;
    
    protected $fillable = [
        'title',
        'image',
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

    public function venues(): HasMany
    {
        return $this->hasMany(Venue::class);
    }
} 