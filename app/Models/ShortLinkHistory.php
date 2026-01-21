<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShortLinkHistory extends Model
{
    protected $table = 'short_link_histories';

    protected $fillable = [
        'short_link_id',
        'user_id',
        'original_url',
        'title',
        'description',
        'is_active',
        'expires_at',
        'action',
        'changes',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'expires_at' => 'datetime',
        'changes' => 'array',
    ];

    public function shortLink(): BelongsTo
    {
        return $this->belongsTo(ShortLink::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
