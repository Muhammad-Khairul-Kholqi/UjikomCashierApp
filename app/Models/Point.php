<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Point extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'sales_id',
        'points_earned',
        'points_used',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function sales()
    {
        return $this->belongsTo(Sales::class);
    }
}
