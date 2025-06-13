<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserBan extends Model
{
    protected $fillable = [
        'user_id',
        'banned_by',
        'reason',
        'banned_at',
        'expires_at',
        'is_permanent'
    ];

    protected $casts = [
        'banned_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_permanent' => 'boolean'
    ];

    /**
     * Get the user that was banned
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the admin who banned the user
     */
    public function bannedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'banned_by');
    }

    /**
     * Check if the ban is still active
     */
    public function isActive(): bool
    {
        if ($this->is_permanent) {
            return true;
        }

        if (!$this->expires_at) {
            return true;
        }

        return $this->expires_at->isFuture();
    }

    /**
     * Scope a query to only include active bans
     */
    public function scopeActive($query)
    {
        return $query->where(function ($query) {
            $query->where('is_permanent', true)
                  ->orWhere('expires_at', '>', now())
                  ->orWhereNull('expires_at');
        });
    }
}
