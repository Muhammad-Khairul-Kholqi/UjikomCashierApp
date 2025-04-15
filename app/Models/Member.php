<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'phone_number', 'address', 'points', 'created_by'];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
