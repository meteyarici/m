<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Product\Models\Product;

class Auction extends Model
{

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function getStartAtFormattedAttribute()
    {
        return $this->start_at->translatedFormat('d F Y H:i');
    }

    public function getEndAtFormattedAttribute()
    {
        return $this->end_at->translatedFormat('d F Y H:i');
    }
}


