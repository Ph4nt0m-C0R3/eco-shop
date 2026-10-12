<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordChallenge extends Model
{
    protected $guarded = ['id'];
    protected $hidden = ['code_hash', 'grant_hash', 'session_hash', 'account_hash'];
    protected function casts(): array
    {
        return array_fill_keys(['expires_at', 'sent_at', 'attempts_at', 'send_window_at', 'verified_at', 'consumed_at'], 'datetime');
    }
}
