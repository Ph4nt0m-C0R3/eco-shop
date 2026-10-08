<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $fillable = [
        'name_en',
        'name_mm',
        'type',
        'account_name',
        'account_number',
        'currency_code',
        'qr_image',
        'icon',
        'is_active',
    ];

    public function getDisplayNameAttribute(): string
    {
        return app()->getLocale() === 'mm' && $this->name_mm
            ? $this->name_mm
            : $this->name_en;
    }
}
