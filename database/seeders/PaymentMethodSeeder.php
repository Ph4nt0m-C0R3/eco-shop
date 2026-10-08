<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    public function run(): void
    {
        PaymentMethod::create([
            'name_en' => 'Cash On Delivery',
            'name_mm' => 'ပစ္စည်းရောက်မှ ငွေချေ',
            'type' => 'cod',
            'currency_code' => 'MMK',
            'icon' => 'default/cod.png',
            'is_active' => true,
        ]);

        PaymentMethod::create([
            'name_en' => 'KBZ Pay',
            'name_mm' => 'KBZ Pay',
            'type' => 'e_wallet',
            'account_name' => 'Aung Myo Pyae',
            'account_number' => '09*****1436',
            'currency_code' => 'MMK',
            'qr_image' => 'default/kbz_qr.png',
            'icon' => 'default/kbz.png',
            'is_active' => true,
        ]);

        PaymentMethod::create([
            'name_en' => 'Wave Pay',
            'name_mm' => 'Wave Pay',
            'type' => 'e_wallet',
            'account_name' => 'Aung Myo Pyae',
            'account_number' => '09*****1436',
            'currency_code' => 'MMK',
            'qr_image' => 'default/wave_qr.png',
            'icon' => 'default/wave.png',
            'is_active' => true,
        ]);

        PaymentMethod::create([
            'name_en' => 'AYA Pay',
            'name_mm' => 'AYA Pay',
            'type' => 'e_wallet',
            'account_name' => 'Aung Myo Pyae',
            'account_number' => '09*****3748',
            'currency_code' => 'MMK',
            'qr_image' => 'default/aya_qr.png',
            'icon' => 'default/aya.png',
            'is_active' => true,
        ]);

        PaymentMethod::create([
            'name_en' => 'Visa',
            'name_mm' => 'Visa',
            'type' => 'stripe',
            'currency_code' => 'USD',
            'icon' => 'default/visa.png',
            'is_active' => true,
        ]);

        PaymentMethod::create([
            'name_en' => 'Master Card',
            'name_mm' => 'Master Card',
            'type' => 'stripe',
            'currency_code' => 'USD',
            'icon' => 'default/mastercard.png',
            'is_active' => true,
        ]);

    }
}
