<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;

class PasswordOtp extends Model
{
    protected $fillable = [
        'email',
        'otp',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    use Prunable;

    public function prunable()
    {
        return static::where('expires_at', '<=', now());
    }
}
