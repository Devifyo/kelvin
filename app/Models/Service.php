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
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
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
}