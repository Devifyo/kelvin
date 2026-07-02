<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class Client extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'logo',
        'website_url',
        'description',
        'is_featured',
        'sort_order',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_featured' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Accessors appended to the model's array/JSON form.
     *
     * @var array<int, string>
     */
    protected $appends = ['logo_url'];

    // --- Accessors ---

    /**
     * Public URL to the client logo (or null if missing).
     */
    public function getLogoUrlAttribute(): ?string
    {
        return $this->logo ? Storage::url($this->logo) : null;
    }

    // --- Query Scopes ---

    /**
     * Scope a query to only include active clients.
     */
    public function scopeActive(Builder $query): void
    {
        $query->where('status', 'active');
    }

    /**
     * Scope a query to only include featured clients.
     */
    public function scopeFeatured(Builder $query): void
    {
        $query->where('is_featured', true);
    }

    /**
     * Scope a query to order clients by their defined sort order.
     */
    public function scopeOrdered(Builder $query): void
    {
        $query->orderBy('sort_order', 'asc')->orderBy('name', 'asc');
    }

    // --- Cache Helpers ---

    /**
     * Cache keys that depend on client data. Forgotten whenever a client changes.
     *
     * @var array<int, string>
     */
    public const CACHE_KEYS = ['clients.featured', 'clients.active_count', 'clients.all_active'];

    /**
     * Invalidate cached client queries. Call after any create/update/delete.
     */
    public static function clearCache(): void
    {
        foreach (self::CACHE_KEYS as $key) {
            Cache::forget($key);
        }
    }
}
