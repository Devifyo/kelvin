<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;

class Post extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id', 
        'category_id', 
        'title', 
        'slug', 
        'featured_image',
        'attachment',
        'attachment_mime_type',
        'excerpt',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'content', // Text Editor Html
        'status', 
        'published_at'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'attachment_url',
        'featured_image_url'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'published_at' => 'datetime',
    ];

    /**
     * Get the route key for the model.
     * This allows automatic route model binding using the 'slug'.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    // --- Relationships ---

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // --- Accessors & Helpers ---

    /**
     * Check if the post has any file attached.
     */
    public function getHasAttachmentAttribute(): bool
    {
        return !empty($this->attachment);
    }

    /**
     * Get the full URL for the attached file using Laravel's Storage facade.
     * This ensures compatibility whether stored locally or on a cloud disk like S3.
     */
    public function getAttachmentUrlAttribute(): ?string
    {
        return $this->attachment ? Storage::url($this->attachment) : null;
    }

    /**
     * Get the full URL for the featured image using Laravel's Storage facade.
     */
    public function getFeaturedImageUrlAttribute(): ?string
    {
        return $this->featured_image ? Storage::url($this->featured_image) : null;
    }

    /**
     * Helper to quickly check if the attachment is a PDF.
     */
    public function getIsPdfAttachmentAttribute(): bool
    {
        return $this->attachment_mime_type === 'application/pdf';
    }

    // --- Query Scopes ---

    /**
     * Scope a query to only include published posts.
     */
    public function scopePublished(Builder $query): void
    {
        $query->where('status', 'published')
              ->where('published_at', '<=', now());
    }
}