<?php

namespace Database\Seeders;

use App\Models\SystemSetting;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SystemSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaults = [
        'app_name' => 'EcoShop',
        'favicon' => 'default/favicon.png',
        'logo' => 'default/logo.png',
        'logo_text' => 'default/logoText.png',
        'contact_email' => 'hello@example.test',
        'contact_phone' => '000-000-0000',
        'address' => 'Hlaing Township, Yangon',
    ];

        foreach ($defaults as $key => $value) {
            SystemSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }
    }
}
