<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PodcastWebinar extends Model
{
    protected $fillable = [
        'title', 'type', 'platform', 'url', 'video_path', 'description',
        'thumbnail_image', 'published_date', 'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'published_date' => 'date',
    ];

    public function getThumbnailUrlAttribute(): ?string
    {
        return $this->thumbnail_image ? asset('storage/' . $this->thumbnail_image) : null;
    }

    public function getVideoUrlAttribute(): ?string
    {
        if ($this->video_path) {
            return asset('storage/' . $this->video_path);
        }
        return $this->url ?: null;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    public function scopeOrdered($query)
    {
        return $query->orderBy('published_date', 'desc');
    }
}
