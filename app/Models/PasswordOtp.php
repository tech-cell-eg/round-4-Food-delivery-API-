<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordOtp extends Model
{
    protected $fillable = ['email', 'otp', 'expires_at'];

    public $timestamps = true;

    protected $dates = ['expires_at'];
    protected $casts = [
    'expires_at' => 'datetime',
];
}
