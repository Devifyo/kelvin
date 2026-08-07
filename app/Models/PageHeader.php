<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/**
 * Editable hero/header text (kicker + H1 + subtitle) for the public listing pages.
 *
 * ── ADDING A NEW PAGE ────────────────────────────────────────────────────────
 *   1. Add an entry to self::PAGES below (defaults become the seeded content).
 *   2. Drop <x-page-header page="your-key" /> into that page's blade file.
 * That's it — the admin module renders the new page automatically.
 */
class PageHeader extends Model
{
    protected $guarded = [];

    /**
     * Registry of every page whose header is editable from the admin panel.
     *
     *  label        — name shown in the admin sidebar list
     *  route        — named route, used for the "view live page" link + preview URL
     *  kicker_class — CSS class the page's own stylesheet expects on the kicker
     *  kicker_style — optional inline style the original markup carried
     *  has_subtitle — false hides the subtitle field (page design has no subtitle)
     *  defaults     — the original hard-coded copy; used for seeding and "Reset"
     */
    public const PAGES = [
        'services' => [
            'label'        => 'Consulting & Training',
            'route'        => 'services.training',
            'kicker_class' => 'kicker',
            'kicker_style' => 'color:var(--copper2);',
            'has_subtitle' => true,
            'defaults'     => [
                'kicker'        => 'Our Expertise',
                'title_regular' => 'Consulting &',
                'title_em'      => 'Training',
                'subtitle'      => 'We offer both consulting and training services. All services are provided on-site at client locations.',
            ],
        ],

        'about' => [
            'label'        => 'About Dr. Kevin Thompson',
            'route'        => 'about',
            'kicker_class' => 'kicker-small',
            'kicker_style' => 'color:var(--copper2);',
            'has_subtitle' => false,
            'defaults'     => [
                'kicker'        => 'Principal Consultant',
                'title_regular' => 'About Dr. Kevin',
                'title_em'      => 'Thompson',
                'subtitle'      => null,
            ],
        ],

        'papers' => [
            'label'        => 'Papers & Presentations',
            'route'        => 'papers',
            'kicker_class' => 'kicker',
            'kicker_style' => null,
            'has_subtitle' => true,
            'defaults'     => [
                'kicker'        => 'Knowledge & Research',
                'title_regular' => 'Papers &',
                'title_em'      => 'Presentations',
                'subtitle'      => 'A comprehensive collection of insights, methodologies, and findings from our extensive engagements in Agile hardware and software development.',
            ],
        ],

        'podcasts-webinars' => [
            'label'        => 'Podcasts & Webinars',
            'route'        => 'podcasts-webinars',
            'kicker_class' => 'kicker',
            'kicker_style' => null,
            'has_subtitle' => true,
            'defaults'     => [
                'kicker'        => 'Media & Appearances',
                'title_regular' => 'Podcasts &',
                'title_em'      => 'Webinars',
                'subtitle'      => "Watch and listen to Dr. Kevin Thompson's latest interviews, keynotes, and deep-dives into Agile hardware transformation.",
            ],
        ],

        'contact' => [
            'label'        => 'Contact',
            'route'        => 'contact',
            'kicker_class' => 'kicker',
            'kicker_style' => 'color:var(--copper2);',
            'has_subtitle' => true,
            // Edited from the dedicated "Contact Page" module, not the shared
            // Page Headers screen — kept here so <x-page-header> still resolves
            // its meta + defaults. Hidden from the Page Headers list to avoid a
            // second editing surface for the same copy.
            'admin_hidden' => true,
            'defaults'     => [
                'kicker'        => "Let's Connect",
                'title_regular' => 'Contact',
                'title_em'      => null,
                'subtitle'      => "Whether you're facing a specific hardware development challenge or want to explore an Agile transformation, we're here to help.",
            ],
        ],

        'blog' => [
            'label'        => 'Agile Insights Blog',
            'route'        => 'blog',
            'kicker_class' => 'kicker',
            'kicker_style' => null,
            'has_subtitle' => true,
            'defaults'     => [
                'kicker'        => 'Insights & Articles',
                'title_regular' => 'Agile',
                'title_em'      => 'Insights',
                'subtitle'      => 'Expert perspectives on the unique challenges, methodologies, and intersection of Agile software and hardware development.',
            ],
        ],

        'clients' => [
            'label'        => 'Client Showcase',
            'route'        => 'clients',
            'kicker_class' => 'kicker',
            'kicker_style' => null,
            'has_subtitle' => true,
            'defaults'     => [
                'kicker'        => 'Our Clients',
                'title_regular' => 'Trusted By',
                'title_em'      => 'Industry Leaders',
                'subtitle'      => 'Over the course of more than 100 engagements, I have partnered with leading hardware, technology, and enterprise organizations to deliver lasting Agile transformation.',
            ],
        ],
    ];

    protected static function booted(): void
    {
        static::saved(fn (self $header) => $header->forgetCache());
        static::deleted(fn (self $header) => $header->forgetCache());
    }

    /**
     * The header for a page, cached. Never returns null — an unsaved instance
     * built from the registry defaults is returned if the row is missing, so
     * the public site can never render a page with an empty <h1>.
     */
    public static function for(string $pageKey): self
    {
        $header = Cache::remember(
            "page_header.{$pageKey}",
            3600,
            fn () => static::where('page_key', $pageKey)->first()
        );

        return $header ?: static::fromDefaults($pageKey);
    }

    /** A non-persisted instance carrying the registry defaults for a page. */
    public static function fromDefaults(string $pageKey): self
    {
        $defaults = static::PAGES[$pageKey]['defaults'] ?? [];

        return new static(array_merge(['page_key' => $pageKey], $defaults));
    }

    /** Registry metadata for a page (label, route, kicker class, …). */
    public static function meta(string $pageKey): array
    {
        return static::PAGES[$pageKey] ?? [];
    }

    /** Pages editable on the shared Page Headers screen (excludes ones managed elsewhere). */
    public static function adminPages(): array
    {
        return array_filter(static::PAGES, fn ($meta) => empty($meta['admin_hidden']));
    }

    public function forgetCache(): void
    {
        Cache::forget("page_header.{$this->page_key}");
    }

    public static function clearCache(): void
    {
        foreach (array_keys(static::PAGES) as $pageKey) {
            Cache::forget("page_header.{$pageKey}");
        }
    }
}
