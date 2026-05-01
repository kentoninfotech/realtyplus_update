<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * CMS row used to power sections of the public landing page.
 * `section` examples: hero_slide, feature, testimonial, faq, stat, setting.
 */
class LandingContent extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'extra' => 'array',
        'is_active' => 'boolean',
    ];

    public function scopeSection($q, string $section)
    {
        return $q->where('section', $section)->where('is_active', true)->orderBy('sort_order');
    }

    /**
     * Convenience accessor for global key/value site settings.
     */
    public static function setting(string $key, $default = null)
    {
        $row = static::where('section', 'setting')->where('key', $key)->first();
        return $row ? ($row->body ?? $row->title ?? $default) : $default;
    }
}
