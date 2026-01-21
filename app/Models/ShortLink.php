<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode as QrCodeGenerator;

class ShortLink extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'category_id',
        'short_code',
        'original_url',
        'title',
        'description',
        'click_count',
        'is_active',
        'expires_at',
        'password',
        'password_protected',
        'qr_code_path',
        'qr_code_size',
        'qr_code_format',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
        'password_protected' => 'boolean',
        'click_count' => 'integer',
        'qr_code_size' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($shortLink) {
            if (empty($shortLink->short_code)) {
                $shortLink->short_code = self::generateUniqueCode();
            }
        });

        static::created(function ($shortLink) {
            // QR kod oluştur
            $shortLink->generateQrCode();
            // Geçmiş kaydı oluştur
            $shortLink->createHistory('created');
        });

        static::updated(function ($shortLink) {
            // Geçmiş kaydı oluştur
            $shortLink->createHistory('updated');
        });

        static::deleted(function ($shortLink) {
            // Geçmiş kaydı oluştur
            $shortLink->createHistory('deleted');
        });
    }

    public static function generateUniqueCode($length = 8): string
    {
        do {
            $code = Str::random($length);
        } while (self::where('short_code', $code)->exists());

        return $code;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function clicks(): HasMany
    {
        return $this->hasMany(ShortLinkClick::class);
    }

    public function history(): HasMany
    {
        return $this->hasMany(ShortLinkHistory::class);
    }

    public function getShortUrlAttribute(): string
    {
        // Önce Settings'ten kontrol et
        $settings = \App\Models\Settings::getSettings();
        $customDomain = $settings->short_link_domain ?? config('app.short_link_domain') ?? env('SHORT_LINK_DOMAIN');
        
        if ($customDomain) {
            // Domain'den http/https ve trailing slash'i temizle
            $customDomain = preg_replace('/^https?:\/\//', '', $customDomain);
            $customDomain = rtrim($customDomain, '/');
            return 'https://' . $customDomain . '/l/' . $this->short_code;
        }
        return route('short-link.redirect', $this->short_code);
    }

    public function getIsExpiredAttribute(): bool
    {
        if (!$this->expires_at) {
            return false;
        }
        
        return $this->expires_at->isPast();
    }

    public function hasPassword(): bool
    {
        return !empty($this->password);
    }

    public function verifyPassword($password): bool
    {
        return password_verify($password, $this->password);
    }

    public function incrementClickCount(): void
    {
        $this->increment('click_count');
    }

    public function generateQrCode(): void
    {
        $size = $this->qr_code_size ?? 300;
        $format = $this->qr_code_format ?? 'png';
        $url = $this->short_url;

        $fileName = 'short-links/qr-codes/' . $this->short_code . '.' . $format;

        try {
            if ($format === 'svg') {
                $qrImage = QrCodeGenerator::size($size)
                    ->format('svg')
                    ->generate($url);
            } else {
                if (extension_loaded('imagick')) {
                    $qrImage = QrCodeGenerator::size($size)
                        ->format('png')
                        ->errorCorrection('H')
                        ->generate($url);
                } else {
                    $format = 'svg';
                    $fileName = 'short-links/qr-codes/' . $this->short_code . '.' . $format;
                    $qrImage = QrCodeGenerator::size($size)
                        ->format('svg')
                        ->generate($url);
                }
            }

            Storage::disk('public')->put($fileName, $qrImage);

            $this->qr_code_path = $fileName;
            $this->qr_code_format = $format;
            $this->saveQuietly();
        } catch (\Exception $e) {
            \Log::error('QR code generation failed for short link: ' . $this->id, [
                'error' => $e->getMessage()
            ]);
        }
    }

    public function createHistory($action): void
    {
        ShortLinkHistory::create([
            'short_link_id' => $this->id,
            'user_id' => auth()->id(),
            'original_url' => $this->original_url,
            'title' => $this->title,
            'description' => $this->description,
            'is_active' => $this->is_active,
            'expires_at' => $this->expires_at,
            'action' => $action,
            'changes' => $this->getChanges(),
        ]);
    }
}
