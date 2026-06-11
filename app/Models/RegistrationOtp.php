<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistrationOtp extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'details',
        'otp',
        'expires_at',
        'is_used',
    ];

    protected $casts = [
        'details' => 'array',
        'expires_at' => 'datetime',
        'is_used' => 'boolean',
    ];
}
