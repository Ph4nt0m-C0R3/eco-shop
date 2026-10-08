<?php

namespace Database\Seeders;

use App\Models\ShippingZone;
use Illuminate\Database\Seeder;

class ShippingZoneSeeder extends Seeder
{
    public function run(): void
    {
        $zones = [
            // Major cities
            ['country' => 'Myanmar', 'name' => 'Yangon', 'fee_mmk' => 3000],
            ['country' => 'Myanmar', 'name' => 'Mandalay', 'fee_mmk' => 3500],
            ['country' => 'Myanmar', 'name' => 'Naypyitaw', 'fee_mmk' => 4000],

            // Regions
            ['country' => 'Myanmar', 'name' => 'Bago Region', 'fee_mmk' => 3500],
            ['country' => 'Myanmar', 'name' => 'Ayeyarwady Region', 'fee_mmk' => 4500],
            ['country' => 'Myanmar', 'name' => 'Magway Region', 'fee_mmk' => 4500],
            ['country' => 'Myanmar', 'name' => 'Sagaing Region', 'fee_mmk' => 5000],
            ['country' => 'Myanmar', 'name' => 'Tanintharyi Region', 'fee_mmk' => 5500],
            ['country' => 'Myanmar', 'name' => 'Kayin State', 'fee_mmk' => 4500],
            ['country' => 'Myanmar', 'name' => 'Mon State', 'fee_mmk' => 4500],

            // States
            ['country' => 'Myanmar', 'name' => 'Shan State', 'fee_mmk' => 5500],
            ['country' => 'Myanmar', 'name' => 'Kachin State', 'fee_mmk' => 6000],
            ['country' => 'Myanmar', 'name' => 'Chin State', 'fee_mmk' => 6500],
            ['country' => 'Myanmar', 'name' => 'Rakhine State', 'fee_mmk' => 5500],
            ['country' => 'Myanmar', 'name' => 'Kayah State', 'fee_mmk' => 5000],
        ];

        foreach ($zones as $zone) {
            ShippingZone::updateOrCreate(
                [
                    'country' => $zone['country'],
                    'name' => $zone['name'],
                ],
                [
                    'fee_mmk' => $zone['fee_mmk'],
                    'is_active' => true,
                ]
            );
        }
    }
}
