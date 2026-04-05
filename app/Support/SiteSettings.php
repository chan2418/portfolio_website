<?php

namespace App\Support;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Cache;

class SiteSettings
{
    public static function all(): array
    {
        return Cache::remember('site-settings:all', now()->addMinutes(15), function (): array {
            return SiteSetting::query()->pluck('value', 'key')->toArray();
        });
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        return static::all()[$key] ?? $default;
    }

    public static function clear(): void
    {
        Cache::forget('site-settings:all');
    }
}
