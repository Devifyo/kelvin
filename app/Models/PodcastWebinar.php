<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
class PodcastWebinar extends Model
{
    protected $fillable = [
        'title', 'type', 'platform', 'url', 'description', 
        'thumbnail_image', 'published_date', 'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'published_date' => 'date',
    ];

    public function getThumbnailUrlAttribute()
    {
        return $this->thumbnail_image ? Storage::url($this->thumbnail_image) : null;
    }
}
