<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'user_id',
        'currency_code',
        'subtotal_mmk',
        'subtotal_usd',
        'tax',
        'shipping_fee',
        'grand_total',
        'status',
        'delivery_type',
        'payment_method_id',
        'payment_method',
        'payment_reference',
        'payment_screenshot',
        'stripe_session_id',
        'payment_status',
        'phone',
        'shipping_address',
        'exchange_rate_mmk',
        'reservation_expires_at',
        'reservation_released_at',
        'inventory_committed_at',
        'paid_at'
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'reservation_expires_at' => 'datetime',
        'reservation_released_at' => 'datetime',
        'inventory_committed_at' => 'datetime',
        'exchange_rate_mmk' => 'decimal:8',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Generate unique order number
    public static function generateOrderNumber()
    {
        return 'ECO-' . strtoupper(uniqid());
    }

    public function getGrandTotalUsdAttribute()
    {
        if ($this->currency_code === 'USD') {
            return $this->grand_total;
        }

        $rate = $this->exchange_rate_mmk ?? ($this->subtotal_usd > 0 && $this->subtotal_mmk > 0
            ? $this->subtotal_mmk / $this->subtotal_usd : Currency::getRate('MMK')); // 1 USD = rate MMK
        return $rate > 0 ? round($this->grand_total / $rate, 2) : 0;
    }

    public function getGrandTotalMmkAttribute()
    {
        if ($this->currency_code === 'MMK') {
            return $this->grand_total;
        }

        $rate = $this->exchange_rate_mmk ?? ($this->subtotal_usd > 0 && $this->subtotal_mmk > 0
            ? $this->subtotal_mmk / $this->subtotal_usd : Currency::getRate('MMK'));
        return round($this->grand_total * $rate, 0);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

}
