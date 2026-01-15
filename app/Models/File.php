<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class File extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'original_name',
        'path',
        'type',
        'mime_type',
        'size',
        'description',
        'category',
        'category_id',
        'is_public',
        'download_count',
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'size' => 'integer',
        'download_count' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function qrCodes(): HasMany
    {
        return $this->hasMany(QrCode::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function qrCodeFiles(): BelongsToMany
    {
        return $this->belongsToMany(QrCode::class, 'qr_code_file')->withPivot('sort_order')->withTimestamps();
    }

    public function getSizeHumanAttribute(): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $size = $this->size;
        $unit = 0;
        
        while ($size >= 1024 && $unit < count($units) - 1) {
            $size /= 1024;
            $unit++;
        }
        
        return round($size, 2) . ' ' . $units[$unit];
    }

    public function getUrlAttribute(): string
    {
        // Download route'unu kullan (CORS header'ları ile)
        return route('media-library.download', $this->id);
    }
    
    public function getStorageUrlAttribute(): string
    {
        // Doğrudan storage URL'i (eski yöntem, geriye dönük uyumluluk için)
        $pathParts = explode('/', $this->path);
        $encodedParts = [];
        foreach ($pathParts as $part) {
            if (end($pathParts) === $part) {
                $encodedParts[] = rawurlencode($part);
            } else {
                $encodedParts[] = $part;
            }
        }
        $encodedPath = implode('/', $encodedParts);
        return asset('storage/' . $encodedPath);
    }
}
