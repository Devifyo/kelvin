<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class FaqSection extends Model
{
    protected $fillable = ['key', 'page', 'name', 'kicker', 'title', 'title_em', 'is_active', 'sort_order'];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function faqs()
    {
        return $this->hasMany(Faq::class)->orderBy('sort_order')->orderBy('id');
    }

    /** Friendly name of the public page this section appears on. */
    public function pageLabel(): string
    {
        return match ($this->page) {
            'home'     => 'Homepage',
            'services' => 'Services Page',
            'training' => 'Training Page',
            'faq'      => 'FAQ Page',
            default    => ucfirst($this->page) . ' Page',
        };
    }

    /** Public URL where this section's FAQs are shown (null if unknown). */
    public function pageUrl(): ?string
    {
        return match ($this->page) {
            'home'     => route('home'),
            'services' => route('services.training'),
            'training' => route('training'),
            'faq'      => route('faq'),
            default    => null,
        };
    }

    public function activeFaqs()
    {
        return $this->faqs()->where('is_active', true);
    }

    /**
     * Cached active sections for a public page (each with its active Q&As).
     * Returns a Collection of FaqSection with `activeFaqs` eager-loaded.
     */
    public static function forPage(string $page)
    {
        return Cache::remember("faq.page.{$page}", 3600, fn () => static::query()
            ->where('page', $page)
            ->where('is_active', true)
            ->with('activeFaqs')
            ->orderBy('sort_order')
            ->get()
            ->filter(fn ($s) => $s->activeFaqs->isNotEmpty())
            ->values());
    }

    /** Flatten a page's sections into [{q,a}] for combined FAQPage JSON-LD. */
    public static function schemaItems(string $page): array
    {
        return static::forPage($page)
            ->flatMap(fn ($s) => $s->activeFaqs->map(fn ($f) => ['q' => $f->question, 'a' => $f->answer]))
            ->all();
    }

    public static function clearCache(): void
    {
        foreach (['home', 'services', 'training', 'faq'] as $page) {
            Cache::forget("faq.page.{$page}");
        }
    }
}
