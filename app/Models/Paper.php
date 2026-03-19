<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paper extends Model
{
    protected $fillable = [
        'title', 'category_id', 'sub_category', 'description', 'file_path', 'is_active', 'sort_order'
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
}
