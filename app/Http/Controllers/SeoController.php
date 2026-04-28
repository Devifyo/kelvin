<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use App\Models\Paper;
use App\Models\Post;
use App\Models\Service;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;

class SeoController extends Controller
{
    public function robots(): Response
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

        return response(implode("\n", $lines) . "\n", 200, [
            'Content-Type' => 'text/plain',
        ]);
    }

    public function sitemap(): Response
    {
        $urls = $this->staticPages();

        if (AppSetting::get('seo_sitemap_blog', '1') === '1') {
            Post::where('status', 'published')
                ->orderByDesc('published_at')
                ->get(['slug', 'updated_at'])
                ->each(function ($post) use (&$urls) {
                    $urls[] = [
                        'loc'        => url('/agile-insights-blog/' . $post->slug),
                        'lastmod'    => $post->updated_at->toAtomString(),
                        'changefreq' => 'monthly',
                        'priority'   => '0.6',
                    ];
                });
        }

        if (AppSetting::get('seo_sitemap_training', '1') === '1') {
            Service::where('type', 'training')
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get(['slug', 'updated_at'])
                ->each(function ($service) use (&$urls) {
                    $urls[] = [
                        'loc'        => url('/agile-training-classes/' . $service->slug),
                        'lastmod'    => $service->updated_at->toAtomString(),
                        'changefreq' => 'monthly',
                        'priority'   => '0.7',
                    ];
                });
        }

        // Deduplicate by loc, keeping first occurrence
        $seen = [];
        $urls = array_filter($urls, function ($url) use (&$seen) {
            if (in_array($url['loc'], $seen, true)) return false;
            $seen[] = $url['loc'];
            return true;
        });

        $xml  = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        foreach ($urls as $url) {
            $xml .= "    <url>\n";
            $xml .= '        <loc>' . htmlspecialchars($url['loc']) . "</loc>\n";
            if (isset($url['lastmod']))    $xml .= '        <lastmod>'    . $url['lastmod']    . "</lastmod>\n";
            if (isset($url['changefreq'])) $xml .= '        <changefreq>' . $url['changefreq'] . "</changefreq>\n";
            if (isset($url['priority']))   $xml .= '        <priority>'   . $url['priority']   . "</priority>\n";
            $xml .= "    </url>\n";
        }

        $xml .= '</urlset>';

        return response($xml, 200, [
            'Content-Type' => 'application/xml; charset=utf-8',
        ]);
    }

    public function llms(): Response
    {
        $appName    = AppSetting::get('app_name', 'Kevin Thompson Ph.D.');
        $siteDesc   = AppSetting::get('seo_llms_description', 'Kevin Thompson Ph.D. provides agile consulting, training, and research for hardware development teams. Expert in Scrum, SAFe, and Lean methodologies applied to complex hardware engineering environments.');
        $extra      = AppSetting::get('seo_llms_extra', '');

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

        return response($content, 200, [
            'Content-Type' => 'text/plain; charset=utf-8',
        ]);
    }

    private function staticPages(): array
    {
        $json = AppSetting::get('seo_sitemap_static_pages');

        $pages = $json ? json_decode($json, true) : \App\Livewire\Admin\AppSettings::DEFAULT_STATIC_PAGES;

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
