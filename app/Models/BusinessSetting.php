<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class BusinessSetting extends Model
{
    protected $fillable = ['business_id', 'key', 'value'];

    /**
     * Get all settings for a business as an associative array.
     */
    public static function forBusiness($businessId)
    {
        if (! $businessId) {
            return [];
        }

        try {
            if (! Schema::hasTable('business_settings')) {
                return [];
            }
        } catch (\Throwable $e) {
            return [];
        }

        return Cache::remember('business_settings.' . $businessId, 300, function () use ($businessId) {
            return self::where('business_id', $businessId)->pluck('value', 'key')->toArray();
        });
    }

    /**
     * Get a single setting value for the current authenticated business.
     */
    public static function get($key, $default = null, $businessId = null)
    {
        $businessId = $businessId ?: optional(Auth::user())->business_id;
        if (! $businessId) {
            return $default;
        }
        $all = self::forBusiness($businessId);
        return array_key_exists($key, $all) && $all[$key] !== null && $all[$key] !== ''
            ? $all[$key]
            : $default;
    }

    /**
     * Set a single setting value for a business.
     */
    public static function set($key, $value, $businessId = null)
    {
        $businessId = $businessId ?: optional(Auth::user())->business_id;
        if (! $businessId) {
            return;
        }
        self::updateOrCreate(
            ['business_id' => $businessId, 'key' => $key],
            ['value' => $value]
        );
        Cache::forget('business_settings.' . $businessId);
    }

    public static function forgetCache($businessId)
    {
        Cache::forget('business_settings.' . $businessId);
    }
}
