<?php

namespace Database\Seeders;

use App\Support\Settings;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        Settings::setMany([
            'pharmacy_name' => config('pharmacy.name'),
            'near_expiry_days' => config('pharmacy.near_expiry_days'),
            'default_min_stock' => config('pharmacy.default_min_stock'),
            'currency_symbol' => config('pharmacy.currency_symbol'),
        ]);
    }
}
