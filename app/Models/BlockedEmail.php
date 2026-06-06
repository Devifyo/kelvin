<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlockedEmail extends Model
{
    protected $fillable = [
        'email', 'blocked_until',
    ];

    protected $casts = [
        'blocked_until' => 'datetime',
    ];

    /**
     * Limit the query to blocks that are still in effect — either
     * indefinite (null) or with an expiry still in the future.
     */
    public function scopeActive($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('blocked_until')
              ->orWhere('blocked_until', '>', now());
        });
    }

    /**
     * Whether the given email currently has an active block.
     */
    public static function isBlocked(string $email): bool
    {
        return static::active()
            ->where('email', $email)
            ->exists();
    }
}
