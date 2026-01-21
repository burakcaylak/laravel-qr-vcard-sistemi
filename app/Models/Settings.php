<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    protected $fillable = [
        'logo_light',
        'logo_dark',
        'favicon',
        'login_image',
        'index_enabled',
        'language',
        'footer_text',
        'short_link_domain',
    ];

    protected $casts = [
        'index_enabled' => 'boolean',
    ];

    /**
     * Get or create settings (with cache)
     */
    public static function getSettings()
    {
        return \Illuminate\Support\Facades\Cache::remember('settings', 3600, function () {
            $settings = static::first();
            if (!$settings) {
                $settings = static::create([
                    'index_enabled' => true,
                    'language' => 'tr',
                ]);
            }
            return $settings;
        });
    }
    
    /**
     * Clear settings cache
     */
    public static function clearCache()
    {
        \Illuminate\Support\Facades\Cache::forget('settings');
    }
    
    protected static function booted()
    {
        static::saved(function () {
            static::clearCache();
        });
        
        static::deleted(function () {
            static::clearCache();
        });
    }
}
