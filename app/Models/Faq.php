<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    protected $fillable = ['faq_section_id', 'question', 'answer', 'sort_order', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function section()
    {
        return $this->belongsTo(FaqSection::class, 'faq_section_id');
    }
}
