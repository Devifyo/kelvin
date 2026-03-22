<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
class Paper extends Model
{
    protected $fillable = [
        'title', 'category_id', 'sub_category', 'description', 'file_path', 'is_active', 'sort_order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationship back to the Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getFileUrlAttribute()
    {
        return $this->file_path ? Storage::url($this->file_path) : '#';
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    } 
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc');
    }
}
