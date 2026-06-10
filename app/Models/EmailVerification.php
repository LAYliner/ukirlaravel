<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailVerification extends Model
{
    use HasFactory;

    protected $table = 'email_verifications';

    protected $fillable = [
        'email',
        'otp_code',
        'attempts',
        'is_locked',
        'locked_until',
        'expires_at',
    ];

    protected $casts = [
        'is_locked' => 'boolean',
        'locked_until' => 'datetime',
        'expires_at' => 'datetime',
    ];
}
