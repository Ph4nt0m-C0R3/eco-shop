<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Database\Seeders\TaxSettingSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ===== SUPER ADMIN ACCOUNT =====
        User::updateOrCreate(
            ['email' => 'webdevamp@gmail.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('Admin@123'),
                'role' => 'superadmin',
                'email_verified_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        );

        // ===== CALL OTHER SEEDERS =====
        $this->call([
            CurrencySeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
            UserSeeder::class,
            SystemSettingSeeder::class,
            PaymentMethodSeeder::class,
            ShippingZoneSeeder::class,
            PostalCodeSeeder::class,
            TaxSettingsSeeder::class,
            ReviewSeeder::class,
        ]);
    }
}
