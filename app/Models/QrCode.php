<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class QrCode extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'file_id',
        'category_id',
        'token',
        'name',
        'page_title',
        'category',
        'requested_by',
        'request_date',
        'description',
        'qr_type',
        'content',
        'size',
        'format',
        'file_path',
        'scan_count',
        'download_count',
        'is_active',
        'expires_at',
    ];

    protected $casts = [
        'request_date' => 'date',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
        'size' => 'integer',
        'scan_count' => 'integer',
        'download_count' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($qrCode) {
            if (empty($qrCode->token)) {
                $qrCode->token = Str::uuid()->toString();
            }
        });
        
        static::created(function ($qrCode) {
            // Eğer file tipi ise ve content boşsa, token URL'ini content olarak ayarla
            if ($qrCode->qr_type === 'file' && $qrCode->file_id && empty($qrCode->content)) {
                $qrCode->content = route('qr.access', $qrCode->token);
                $qrCode->saveQuietly();
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function file(): BelongsTo
    {
        return $this->belongsTo(File::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function files(): BelongsToMany
    {
        return $this->belongsToMany(File::class, 'qr_code_file')->withPivot(['sort_order', 'button_name'])->withTimestamps()->orderBy('qr_code_file.sort_order');
    }

    public function getQrUrlAttribute(): string
    {
        return route('qr.access', $this->token);
    }

    public function getIsExpiredAttribute(): bool
    {
        if (!$this->expires_at) {
            return false;
        }
        
        return $this->expires_at->isPast();
    }
    
    /**
     * Generate QR code image.
     */
    public function generateQrImage()
    {
        $content = $this->content;
        $size = $this->size;
        $format = $this->format;

        $fileName = 'qr-codes/' . $this->token . '.' . $format;

        try {
            $qrCodeGenerator = \SimpleSoftwareIO\QrCode\Facades\QrCode::class;
            
            if ($format === 'svg') {
                $qrImage = \SimpleSoftwareIO\QrCode\Facades\QrCode::size($size)
                    ->format('svg')
                    ->generate($content);
            } else {
                // PNG formatı için imagick kontrolü
                if (extension_loaded('imagick')) {
                    // Imagick yüklüyse PNG oluştur
                    $qrImage = \SimpleSoftwareIO\QrCode\Facades\QrCode::size($size)
                        ->format('png')
                        ->errorCorrection('H')
                        ->generate($content);
                } else {
                    // Imagick yoksa SVG kullan ve format'ı güncelle
                    \Log::warning('PNG format requested but imagick not available, using SVG instead');
                    $format = 'svg';
                    $fileName = 'qr-codes/' . $this->token . '.' . $format;
                    $qrImage = \SimpleSoftwareIO\QrCode\Facades\QrCode::size($size)
                        ->format('svg')
                        ->generate($content);
                }
            }
        } catch (\Exception $e) {
            // Imagick hatası varsa SVG kullan
            if (strpos($e->getMessage(), 'imagick') !== false || strpos($e->getMessage(), 'Imagick') !== false) {
                \Log::warning('Imagick error, using SVG: ' . $e->getMessage());
                $format = 'svg';
                $fileName = 'qr-codes/' . $this->token . '.' . $format;
                $qrImage = \SimpleSoftwareIO\QrCode\Facades\QrCode::size($size)
                    ->format('svg')
                    ->generate($content);
            } else {
                // Diğer hatalar için tekrar dene
                throw $e;
            }
        }

        \Illuminate\Support\Facades\Storage::disk('public')->put($fileName, $qrImage);

        $this->file_path = $fileName;
        $this->format = $format;
        $this->saveQuietly();
    }
}
