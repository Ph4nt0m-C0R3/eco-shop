<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Currency::create([
            'code' => 'USD',
            'rate' => 1,
            'is_base' => true,
        ]);

        Currency::create([
            'code' => 'MMK',
            'rate' => 5000, // Admin can change later
        ]);
    }

}
