<?php

namespace App\Helpers;

use App\Models\Category;
use App\Models\Settings;
use Illuminate\Support\Facades\Cache;

class CacheHelper
{
    /**
     * Cache süreleri (saniye cinsinden)
     */
    const TTL_SHORT = 300;      // 5 dakika
    const TTL_MEDIUM = 1800;    // 30 dakika
    const TTL_LONG = 3600;      // 1 saat
    const TTL_VERY_LONG = 86400; // 24 saat

    /**
     * Aktif kategorileri cache'den al veya cache'le
     */
    public static function getActiveCategories()
    {
        return Cache::remember('categories.active', self::TTL_LONG, function () {
            return Category::where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get();
        });
    }

    /**
     * Tüm kategorileri cache'den al veya cache'le
     */
    public static function getAllCategories()
    {
        return Cache::remember('categories.all', self::TTL_LONG, function () {
            return Category::orderBy('sort_order')
                ->orderBy('name')
                ->get();
        });
    }

    /**
     * Settings'i cache'den al veya cache'le
     */
    public static function getSettings()
    {
        return Cache::remember('settings', self::TTL_VERY_LONG, function () {
            return Settings::first();
        });
    }

    /**
     * Belirli bir setting değerini cache'den al
     */
    public static function getSetting($key, $default = null)
    {
        $settings = self::getSettings();
        return $settings->{$key} ?? $default;
    }

    /**
     * Kategori cache'ini temizle
     */
    public static function clearCategoryCache()
    {
        Cache::forget('categories.active');
        Cache::forget('categories.all');
    }

    /**
     * Settings cache'ini temizle
     */
    public static function clearSettingsCache()
    {
        Cache::forget('settings');
    }

    /**
     * Dashboard istatistiklerini cache'den al veya cache'le
     */
    public static function getDashboardStats()
    {
        return Cache::remember('dashboard.stats', self::TTL_SHORT, function () {
            return [
                'total_files' => \App\Models\File::count(),
                'total_users' => \App\Models\User::count(),
                'total_qr_code_scans' => \App\Models\QrCode::sum('scan_count'),
                'total_vcard_scans' => \App\Models\VCard::sum('scan_count'),
                'total_short_links' => \App\Models\ShortLink::count(),
                'total_short_link_clicks' => \App\Models\ShortLinkClick::count(),
                'total_brochures' => \App\Models\Brochure::count(),
                'total_brochure_views' => \App\Models\Brochure::sum('view_count'),
            ];
        });
    }

    /**
     * Dashboard cache'ini temizle
     */
    public static function clearDashboardCache()
    {
        Cache::forget('dashboard.stats');
    }

    /**
     * Tüm cache'leri temizle
     */
    public static function clearAll()
    {
        self::clearCategoryCache();
        self::clearSettingsCache();
        self::clearDashboardCache();
    }

    /**
     * Belirli bir key için cache'den al veya callback ile cache'le
     */
    public static function remember($key, $ttl, $callback)
    {
        return Cache::remember($key, $ttl, $callback);
    }

    /**
     * Belirli bir key'i cache'den sil
     */
    public static function forget($key)
    {
        return Cache::forget($key);
    }
}
