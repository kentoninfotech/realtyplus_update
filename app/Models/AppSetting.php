<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class AppSetting extends Model
{
    protected $fillable = ['key', 'value'];

    public static function get($key, $default = null)
    {
        try {
            if (! Schema::hasTable('app_settings')) {
                return $default;
            }
        } catch (\Throwable $e) {
            return $default;
        }

        $all = Cache::rememberForever('app_settings.all', function () {
            return self::pluck('value', 'key')->toArray();
        });

        return array_key_exists($key, $all) ? $all[$key] : $default;
    }

    public static function set($key, $value)
    {
        self::updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::forget('app_settings.all');
    }

    public static function forgetCache()
    {
        Cache::forget('app_settings.all');
    }
}
