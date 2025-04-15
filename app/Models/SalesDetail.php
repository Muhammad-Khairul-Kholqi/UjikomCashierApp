<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesDetail extends Model
{
    protected $fillable = [
        'sales_id',
        'product_id',
        'quantity',
        'price',
        'subtotal'
    ];

    public function sales()
    {
        return $this->belongsTo(Sales::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
