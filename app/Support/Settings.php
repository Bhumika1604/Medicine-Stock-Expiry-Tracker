<?php

namespace App\Support;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

/**
 * Thin wrapper around the `settings` table, backed by config/pharmacy.php
 * defaults. Values are cached for the request lifecycle via Laravel's cache
 * so repeated Settings::get() calls (e.g. inside a loop of batches) don't
 * hit the database each time.
 */
class Settings
{
    protected const CACHE_KEY = 'app_settings';

    public static function all(): array
    {
        return Cache::rememberForever(self::CACHE_KEY, function () {
            $defaults = [
                'pharmacy_name' => config('pharmacy.name'),
                'near_expiry_days' => config('pharmacy.near_expiry_days'),
                'default_min_stock' => config('pharmacy.default_min_stock'),
                'currency_symbol' => config('pharmacy.currency_symbol'),
            ];

            $stored = Setting::query()->pluck('value', 'key')->toArray();

            return array_merge($defaults, array_filter($stored, fn ($v) => $v !== null && $v !== ''));
        });
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        $value = self::all()[$key] ?? $default;

        return is_numeric($value) && ! str_contains((string) $value, '.') ? (int) $value : $value;
    }

    public static function set(string $key, mixed $value): void
    {
        Setting::query()->updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::forget(self::CACHE_KEY);
    }

    public static function setMany(array $values): void
    {
        foreach ($values as $key => $value) {
            Setting::query()->updateOrCreate(['key' => $key], ['value' => $value]);
        }
        Cache::forget(self::CACHE_KEY);
    }
}
