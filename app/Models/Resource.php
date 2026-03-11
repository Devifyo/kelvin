<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    protected $fillable = [
        'category_id', 'title', 'slug', 'description', 
        'file_path', 'resource_type', 'is_active'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
