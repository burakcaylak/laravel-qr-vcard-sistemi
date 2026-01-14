<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class VCardTemplate extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'logo_path',
        'color',
        'background_path',
        'facebook_url',
        'instagram_url',
        'x_url',
        'linkedin_url',
        'youtube_url',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function vCards(): HasMany
    {
        return $this->hasMany(VCard::class);
    }
}
