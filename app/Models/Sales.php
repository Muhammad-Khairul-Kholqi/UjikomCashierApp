<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    protected $fillable = [
        'employee_id',
        'member_id',
        'total_amount',
        'status',
        'payment',
        'change'
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function salesDetails()
    {
        return $this->hasMany(SalesDetail::class);
    }

    public function points()
    {
        return $this->hasMany(Point::class, 'sales_id');
    }

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }
}
