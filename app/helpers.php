<?php

use App\Models\SystemSetting;
use Illuminate\Support\Facades\Cache;

if (!function_exists('setting')) {
    function setting($key, $default = null) {
        $settings = Cache::rememberForever('system_settings', function () {
            return SystemSetting::pluck('value', 'key')->toArray();
        });

        return $settings[$key] ?? $default;
    }
}

if (!function_exists('format_money')) {
    function format_money($amount, $currency)
    {
        $currency = strtoupper($currency);

        return match($currency) {

            'USD' => '$ ' . number_format($amount, 2),

            'MMK' => number_format(round($amount), 0) . ' Ks.',

            default => number_format($amount, 2),
        };
    }
}
