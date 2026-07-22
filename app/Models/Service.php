<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Service extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'slug',
        'type',
        'short_description',
        'featured_image',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'content',
        'learning_objectives',
        'audience',
        'prerequisites',
        'length',
        'topics',
        'is_active',
        'sort_order',
        'icon',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'topics' => 'array',
    ];

    /**
     * Get the route key for the model.
     * This allows implicit route model binding using the 'slug' instead of 'id'.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    // --- Accessors ---

    /**
     * Determine if the service is a training class.
     */
    public function getIsTrainingAttribute(): bool
    {
        return $this->type === 'training';
    }

    /**
     * Determine if the service is a consulting engagement.
     */
    public function getIsConsultingAttribute(): bool
    {
        return $this->type === 'consulting';
    }

    // --- Query Scopes ---

    /**
     * Scope a query to only include active services.
     */
    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }

    /**
     * Scope a query to only include training services.
     */
    public function scopeTraining(Builder $query): void
    {
        $query->where('type', 'training');
    }

    /**
     * Scope a query to only include consulting services.
     * $query->where('type', 'consulting');
     */
    public function scopeConsulting(Builder $query): void
    {
        $query->where('type', 'consulting');    
    }

    /**
     * Scope a query to order services by their defined sort order.
     */
    public function scopeOrdered(Builder $query): void
    {
        $query->orderBy('sort_order', 'asc');
    }

    /**
     * The <title> for this service's page.
     *
     * An explicit meta_title always wins. Otherwise we build one, because a bare
     * course name ("Advanced Product Owner", 22 chars) carries no brand and no
     * indication that it is training — it wastes most of the available SERP
     * width. "Training" is only appended when the name doesn't already imply it
     * and the result still fits inside the ~60-char display limit.
     */
    public function seoTitle(): string
    {
        if (filled($this->meta_title)) {
            return $this->meta_title;
        }

        $brand = config('app.name');
        $base  = trim((string) $this->title);

        if ($this->type === 'training' && ! str_contains(mb_strtolower($base), 'training')) {
            $withKeyword = "{$base} Training";

            if (mb_strlen("{$withKeyword} | {$brand}") <= 60) {
                $base = $withKeyword;
            }
        }

        return "{$base} | {$brand}";
    }
}