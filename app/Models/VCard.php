<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class VCard extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'category_id',
        'template_id',
        // Turkish fields
        'name_tr',
        'title_tr',
        'phone_tr',
        'email_tr',
        'company_tr',
        'address_tr',
        'company_phone_tr',
        'extension_tr',
        'fax_tr',
        'mobile_phone_tr',
        'website_tr',
        // English fields
        'name_en',
        'title_en',
        'phone_en',
        'email_en',
        'company_en',
        'address_en',
        'company_phone_en',
        'extension_en',
        'fax_en',
        'mobile_phone_en',
        'website_en',
        // Common fields
        'email',
        'phone',
        'mobile_phone',
        'website',
        'image_path',
        // QR Code related
        'token',
        'is_active',
        'expires_at',
        'scan_count',
        'file_path',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'expires_at' => 'datetime',
        'scan_count' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($vCard) {
            if (empty($vCard->token)) {
                $vCard->token = Str::uuid()->toString();
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(VCardTemplate::class);
    }

    public function getIsExpiredAttribute(): bool
    {
        if (!$this->expires_at) {
            return false;
        }
        
        return $this->expires_at->isPast();
    }

    /**
     * Get localized field value based on current locale
     */
    public function getLocalizedField($fieldName, $locale = null)
    {
        $locale = $locale ?? app()->getLocale();
        $suffix = $locale === 'en' ? '_en' : '_tr';
        
        $localizedField = $fieldName . $suffix;
        
        // If localized field exists and has value, return it
        if (isset($this->attributes[$localizedField]) && !empty($this->attributes[$localizedField])) {
            return $this->attributes[$localizedField];
        }
        
        // Fallback to common field if exists
        if (isset($this->attributes[$fieldName]) && !empty($this->attributes[$fieldName])) {
            return $this->attributes[$fieldName];
        }
        
        // Fallback to other language
        $fallbackSuffix = $locale === 'en' ? '_tr' : '_en';
        $fallbackField = $fieldName . $fallbackSuffix;
        
        if (isset($this->attributes[$fallbackField]) && !empty($this->attributes[$fallbackField])) {
            return $this->attributes[$fallbackField];
        }
        
        return null;
    }
}
