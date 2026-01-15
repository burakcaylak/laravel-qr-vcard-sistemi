<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Brochure extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'category_id',
        'file_id',
        'name',
        'description',
        'pdf_path',
        'background_type', // 'image' or 'color'
        'background_image_path',
        'background_color',
        'token',
        'qr_code_path',
        'is_active',
        'expires_at',
        'view_count',
        'download_count',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'expires_at' => 'datetime',
        'view_count' => 'integer',
        'download_count' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($brochure) {
            if (empty($brochure->token)) {
                $brochure->token = Str::uuid()->toString();
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

    public function file(): BelongsTo
    {
        return $this->belongsTo(File::class);
    }

    public function getIsExpiredAttribute(): bool
    {
        if (!$this->expires_at) {
            return false;
        }
        
        return $this->expires_at->isPast();
    }

    public function getAccessUrlAttribute(): string
    {
        return route('brochure.access', $this->token);
    }
}
