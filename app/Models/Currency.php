<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    protected $fillable = [
        'code',
        'rate',
        'is_base',
        'is_active',
    ];

    public static function getRate(string $code): float
    {
        return static::where('code', $code)
            ->where('is_active', true)
            ->value('rate') ?? 1;
    }
}
