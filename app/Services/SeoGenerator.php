<?php

namespace App\Services;

use App\Models\AppSetting;
use App\Models\Paper;
use App\Models\Post;
use App\Models\Service;
use App\Livewire\Admin\AppSettings;
use Illuminate\Support\Carbon;

class SeoGenerator
{
    public static function generateAll(): void
    {
        static::generateRobots();
        static::generateSitemap();
        static::generateLlms();
    }

    public static function generateRobots(): void
    {
        $extra = AppSetting::get('seo_robots_disallow_extra', '');

        $lines = [
            'User-agent: *',
            'Disallow: /admin',
            'Disallow: /login',
            'Disallow: /livewire',
            'Disallow: /_ignition',
        ];

        if (trim($extra) !== '') {
            $lines[] = '';
            foreach (explode("\n", $extra) as $line) {
                $lines[] = trim($line);
            }
        }

        $lines[] = '';
        $lines[] = 'Sitemap: ' . url('/sitemap.xml');

        file_put_contents(public_path('robots.txt'), implode("\n", $lines) . "\n");
    }

    public static function generateSitemap(): void
    {
        $urls = static::staticPages();

        // Decorate the homepage and About page with the canonical headshot
        // so they appear in Google Images.
        $portraitAbsolute = url('/img/frontend/' . rawurlencode('Dr. Kevin Thompson.webp'));
        $portraitImage = [
            'loc'     => $portraitAbsolute,
            'caption' => 'Dr. Kevin Thompson, Ph.D. — Agile hardware development consultant',
            'title'   => 'Dr. Kevin Thompson, Ph.D.',
        ];
        foreach ($urls as &$staticUrl) {
            if ($staticUrl['loc'] === url('/') || $staticUrl['loc'] === url('/about-kevin-thompson')) {
                $staticUrl['images'][] = $portraitImage;
            }
        }
        unset($staticUrl);

        if (AppSetting::get('seo_sitemap_blog', '1') === '1') {
            Post::where('status', 'published')
                ->orderByDesc('published_at')
                ->get(['slug', 'title', 'excerpt', 'featured_image', 'updated_at'])
                ->each(function ($post) use (&$urls) {
                    $entry = [
                        'loc'        => url('/agile-insights-blog/' . $post->slug),
                        'lastmod'    => $post->updated_at->toAtomString(),
                        'changefreq' => 'monthly',
                        'priority'   => '0.6',
                    ];
                    if (! empty($post->featured_image)) {
                        $entry['images'][] = [
                            'loc'     => url(\Illuminate\Support\Facades\Storage::url($post->featured_image)),
                            'title'   => $post->title,
                            'caption' => $post->excerpt ?: $post->title,
                        ];
                    }
                    $urls[] = $entry;
                });
        }

        if (AppSetting::get('seo_sitemap_training', '1') === '1') {
            Service::where('type', 'training')
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get(['slug', 'title', 'short_description', 'featured_image', 'updated_at'])
                ->each(function ($service) use (&$urls) {
                    $entry = [
                        'loc'        => url('/agile-training-classes/' . $service->slug),
                        'lastmod'    => $service->updated_at->toAtomString(),
                        'changefreq' => 'monthly',
                        'priority'   => '0.7',
                    ];
                    if (! empty($service->featured_image)) {
                        $entry['images'][] = [
                            'loc'     => url(\Illuminate\Support\Facades\Storage::url($service->featured_image)),
                            'title'   => $service->title,
                            'caption' => $service->short_description ?: $service->title,
                        ];
                    }
                    $urls[] = $entry;
                });
        }

        $seen = [];
        $urls = array_filter($urls, function ($url) use (&$seen) {
            if (in_array($url['loc'], $seen, true)) return false;
            $seen[] = $url['loc'];
            return true;
        });

        $xml  = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"' . "\n";
        $xml .= '        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">' . "\n";

        foreach ($urls as $url) {
            $xml .= "    <url>\n";
            $xml .= '        <loc>' . htmlspecialchars($url['loc']) . "</loc>\n";
            if (isset($url['lastmod']))    $xml .= '        <lastmod>'    . $url['lastmod']    . "</lastmod>\n";
            if (isset($url['changefreq'])) $xml .= '        <changefreq>' . $url['changefreq'] . "</changefreq>\n";
            if (isset($url['priority']))   $xml .= '        <priority>'   . $url['priority']   . "</priority>\n";

            if (! empty($url['images'])) {
                foreach ($url['images'] as $img) {
                    $xml .= "        <image:image>\n";
                    $xml .= '            <image:loc>' . htmlspecialchars($img['loc']) . "</image:loc>\n";
                    if (! empty($img['title']))   $xml .= '            <image:title>'   . htmlspecialchars($img['title'])   . "</image:title>\n";
                    if (! empty($img['caption'])) $xml .= '            <image:caption>' . htmlspecialchars($img['caption']) . "</image:caption>\n";
                    $xml .= "        </image:image>\n";
                }
            }

            $xml .= "    </url>\n";
        }

        $xml .= '</urlset>';

        file_put_contents(public_path('sitemap.xml'), $xml);
    }

    public static function generateLlms(): void
    {
        $appName  = AppSetting::get('app_name', 'Kevin Thompson Ph.D.');
        $siteDesc = AppSetting::get('seo_llms_description', 'Kevin Thompson Ph.D. provides agile consulting, training, and research for hardware development teams.');
        $extra    = AppSetting::get('seo_llms_extra', '');

        $posts = Post::where('status', 'published')
            ->orderByDesc('published_at')
            ->limit(30)
            ->get(['title', 'slug', 'excerpt']);

        $trainings = Service::where('type', 'training')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get(['title', 'slug', 'short_description']);

        $papers = Paper::where('is_active', true)
            ->orderBy('sort_order')
            ->get(['title', 'description', 'sub_category']);

        $content = view('seo.llms', compact('appName', 'siteDesc', 'extra', 'posts', 'trainings', 'papers'))->render();

        file_put_contents(public_path('llms.txt'), $content);
    }

    private static function staticPages(): array
    {
        $json  = AppSetting::get('seo_sitemap_static_pages');
        $pages = $json ? json_decode($json, true) : AppSettings::DEFAULT_STATIC_PAGES;

        return collect($pages)
            ->filter(fn($p) => ($p['enabled'] ?? true) && trim($p['url'] ?? '') !== '')
            ->map(fn($p) => [
                'loc'        => url($p['url']),
                'lastmod'    => Carbon::now()->toAtomString(),
                'changefreq' => $p['changefreq'] ?? 'monthly',
                'priority'   => $p['priority']   ?? '0.5',
            ])
            ->values()
            ->all();
    }
}
