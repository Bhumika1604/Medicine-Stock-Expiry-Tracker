<?php

// Fallback / bootstrap defaults for application settings.
// Once the app boots, the `settings` table (see App\Models\Setting) is the
// source of truth and is what the Settings page reads/writes. These env
// values are only used to seed that table on first run.
return [
    'name' => env('PHARMACY_NAME', 'MediTrack Pharmacy'),
    'near_expiry_days' => (int) env('NEAR_EXPIRY_DAYS', 30),
    'default_min_stock' => (int) env('DEFAULT_MIN_STOCK', 10),
    'currency_symbol' => env('CURRENCY_SYMBOL', '₹'),
];
