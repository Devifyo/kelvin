<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AboutPageContent extends Model
{
    protected $table = 'about_page_contents';

    protected $guarded = [];

    protected $casts = [
        'education_list' => 'array',
    ];
}
