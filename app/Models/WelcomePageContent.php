<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WelcomePageContent extends Model
{
    protected $table = 'welcome_page_contents';

    protected $guarded = [];

    protected $casts = [
        'pain_list' => 'array',
        'section_order' => 'array',
        'trusted_enabled' => 'boolean',
        'principal_portrait_enabled' => 'boolean',
    ];
}
