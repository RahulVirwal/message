<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserData extends Model
{
    use HasFactory;

    protected $table = 'user_data';

    protected $fillable = [
        'user_id',
        'name',
        'state',
        'city',
        'country',
        'pincode',
        'email',
        'mobile_no',
        'messages',
    ];

    protected $casts = [
        'messages' => 'array', // Automatically casts messages to an array
    ];
}

