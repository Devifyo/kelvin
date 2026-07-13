<?php

namespace App\Livewire\Admin;

use App\Models\AppSetting;
use App\Models\Post;
use App\Models\Service;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.admin', ['title' => 'App Settings'])]
class AppSettings extends Component
{
    use WithFileUploads;

    #[Url]
    public string $tab = 'general';

    // ── General ───────────────────────────────────────────────────────────
    public string $appName = '';

    // ── Colors ────────────────────────────────────────────────────────────
    public string $colorCopper = '';

    public string $colorSlate = '';

    // ── SEO: Meta Defaults ────────────────────────────────────────────────
    public string $seoTitleSuffix = '';

    public string $seoDefaultDesc = '';

    public string $seoOgImage = '';

    // ── SEO: Social & Open Graph ──────────────────────────────────────────
    public string $seoTwitterHandle = '';

    public string $seoLinkedinUrl = '';

    // ── SEO: Structured Data ──────────────────────────────────────────────
    public string $seoSchemaJobTitle = '';

    public string $seoSchemaOrgName = '';

    // ── SEO: Analytics ────────────────────────────────────────────────────
    public string $seoGa4Id = '';

    public string $seoGtmId = '';

    // ── SEO: Verification ─────────────────────────────────────────────────
    public string $seoGoogleVerify = '';

    public string $seoBingVerify = '';

    // ── SEO: Crawler Files ────────────────────────────────────────────────
    public string $robotsDisallowExtra = '';

    public array $sitemapStaticPages = [];

    public bool $sitemapBlog = true;

    public bool $sitemapTraining = true;

    public string $llmsDescription = '';

    public string $llmsExtra = '';

    public const DEFAULT_STATIC_PAGES = [
        ['url' => '/',                                        'changefreq' => 'weekly',  'priority' => '1.0', 'enabled' => true],
        ['url' => '/about-kevin-thompson',                    'changefreq' => 'monthly', 'priority' => '0.8', 'enabled' => true],
        ['url' => '/agile-consulting-services',               'changefreq' => 'monthly', 'priority' => '0.8', 'enabled' => true],
        ['url' => '/agile-hardware-papers-and-presentations', 'changefreq' => 'weekly',  'priority' => '0.7', 'enabled' => true],
        ['url' => '/agile-insights-blog',                     'changefreq' => 'daily',   'priority' => '0.7', 'enabled' => true],
        ['url' => '/podcasts-webinars',                       'changefreq' => 'weekly',  'priority' => '0.6', 'enabled' => true],
        ['url' => '/clients',                                 'changefreq' => 'monthly', 'priority' => '0.7', 'enabled' => true],
        ['url' => '/faq',                                     'changefreq' => 'monthly', 'priority' => '0.6', 'enabled' => true],
        ['url' => '/contact-us',                              'changefreq' => 'monthly', 'priority' => '0.6', 'enabled' => true],
    ];

    // ── Icons ─────────────────────────────────────────────────────────────
    #[Validate('nullable|image|mimes:png,jpg,jpeg,webp|max:2048')]
    public $newAppIcon = null;

    #[Validate('nullable|image|mimes:png,jpg,jpeg|max:1024')]
    public $newFavicon = null;

    #[Validate('nullable|image|mimes:png,jpg,jpeg,webp|max:5120')]
    public $newOgImage = null;

    public ?string $currentIconUrl = null;

    public ?string $currentFaviconUrl = null;

    public ?string $currentOgImageUrl = null;

    public ?string $currentOgImageDimensions = null;

    // ── Lifecycle ─────────────────────────────────────────────────────────

    public function mount(): void
    {
        $this->appName = AppSetting::get('app_name');
        $this->colorCopper = AppSetting::get('color_copper', AppSetting::DEFAULTS['color_copper']);
        $this->colorSlate = AppSetting::get('color_slate', AppSetting::DEFAULTS['color_slate']);

        $this->currentIconUrl = $this->resolveStorageUrl(AppSetting::get('app_icon'));
        $this->currentFaviconUrl = $this->resolveStorageUrl(AppSetting::get('favicon'));

        // Meta defaults
        $this->seoTitleSuffix = AppSetting::get('seo_title_suffix', '');
        $this->seoDefaultDesc = AppSetting::get('seo_default_desc')
            ?: 'Expert consulting, training, and methodologies bridging the gap between hardware engineering and Agile software development.';
        $this->seoOgImage = AppSetting::get('seo_og_image', '');
        $this->refreshOgImagePreview();

        // Social
        $this->seoTwitterHandle = AppSetting::get('seo_twitter_handle', '');
        $this->seoLinkedinUrl = AppSetting::get('seo_linkedin_url', '');

        // Schema.org
        $this->seoSchemaJobTitle = AppSetting::get('seo_schema_job_title') ?: 'Agile Consultant & Trainer, Ph.D.';
        $this->seoSchemaOrgName = AppSetting::get('seo_schema_org_name') ?: 'Kevin Thompson Ph.D. Consulting';

        // Analytics
        $this->seoGa4Id = AppSetting::get('seo_ga4_id', '');
        $this->seoGtmId = AppSetting::get('seo_gtm_id', '');

        // Verification
        $this->seoGoogleVerify = AppSetting::get('seo_google_verify', '');
        $this->seoBingVerify = AppSetting::get('seo_bing_verify', '');

        // Crawler files
        $this->robotsDisallowExtra = AppSetting::get('seo_robots_disallow_extra')
            ?: "Disallow: /telescope\nDisallow: /horizon\nDisallow: /storage/app/";

        $pagesJson = AppSetting::get('seo_sitemap_static_pages');
        $this->sitemapStaticPages = $pagesJson ? json_decode($pagesJson, true) : self::DEFAULT_STATIC_PAGES;
        $this->sitemapBlog = AppSetting::get('seo_sitemap_blog', '1') === '1';
        $this->sitemapTraining = AppSetting::get('seo_sitemap_training', '1') === '1';
        $this->llmsDescription = AppSetting::get('seo_llms_description')
            ?: 'Kevin Thompson Ph.D. provides expert agile consulting, training, and research for hardware development teams. Specializing in Scrum, SAFe, Kanban, and Lean methodologies applied to complex hardware and software engineering environments.';
        $this->llmsExtra = AppSetting::get('seo_llms_extra', '');
    }

    // ── Actions ───────────────────────────────────────────────────────────

    public function saveGeneral(): void
    {
        $this->validate([
            'appName' => 'required|string|max:80',
        ]);

        AppSetting::set('app_name', trim($this->appName));
        $this->dispatch('notify', message: 'App name updated.', type: 'success');
        $this->js('setTimeout(() => window.location.reload(), 800)');
    }

    public function saveColors(): void
    {
        $this->validate([
            'colorCopper' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'colorSlate' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
        ]);

        AppSetting::set('color_copper', $this->colorCopper);
        AppSetting::set('color_slate', $this->colorSlate);

        $this->dispatch('notify', message: 'Colors saved.', type: 'success');
        $this->js('setTimeout(() => window.location.reload(), 800)');
    }

    public function resetColors(): void
    {
        $this->colorCopper = AppSetting::DEFAULTS['color_copper'];
        $this->colorSlate = AppSetting::DEFAULTS['color_slate'];

        AppSetting::set('color_copper', $this->colorCopper);
        AppSetting::set('color_slate', $this->colorSlate);

        $this->dispatch('notify', message: 'Colors reset to defaults.', type: 'success');
        $this->js('setTimeout(() => window.location.reload(), 800)');
    }

    public function saveIcons(): void
    {
        $this->validateOnly('newAppIcon');
        $this->validateOnly('newFavicon');

        if ($this->newAppIcon) {
            $old = AppSetting::get('app_icon');
            if ($old) {
                Storage::disk('public')->delete($old);
            }

            $path = $this->newAppIcon->storeAs('app-settings', 'icon.'.$this->newAppIcon->getClientOriginalExtension(), 'public');
            AppSetting::set('app_icon', $path);
            $this->currentIconUrl = asset('storage/'.$path);
            $this->newAppIcon = null;
        }

        if ($this->newFavicon) {
            $old = AppSetting::get('favicon');
            if ($old) {
                Storage::disk('public')->delete($old);
            }

            $path = $this->newFavicon->storeAs('app-settings', 'favicon.'.$this->newFavicon->getClientOriginalExtension(), 'public');
            AppSetting::set('favicon', $path);
            $this->currentFaviconUrl = asset('storage/'.$path);
            $this->newFavicon = null;
        }

        $this->dispatch('notify', message: 'Icons updated successfully.', type: 'success');
        $this->js('setTimeout(() => window.location.reload(), 800)');
    }

    public function removeAppIcon(): void
    {
        $path = AppSetting::get('app_icon');
        if ($path) {
            Storage::disk('public')->delete($path);
        }
        AppSetting::set('app_icon', null);
        $this->currentIconUrl = null;
        $this->dispatch('notify', message: 'App icon removed.', type: 'success');
    }

    public function removeFavicon(): void
    {
        $path = AppSetting::get('favicon');
        if ($path) {
            Storage::disk('public')->delete($path);
        }
        AppSetting::set('favicon', null);
        $this->currentFaviconUrl = null;
        $this->dispatch('notify', message: 'Favicon removed.', type: 'success');
    }

    public function uploadOgImage(): void
    {
        $this->validateOnly('newOgImage');

        if (! $this->newOgImage) {
            $this->dispatch('notify', message: 'Please choose an image first.', type: 'warning');

            return;
        }

        // Replace the previous uploaded OG image (if any) so we don't accumulate orphans.
        $existing = AppSetting::get('seo_og_image');
        if ($existing && ! preg_match('#^https?://#i', $existing)) {
            $oldPath = preg_replace('#^/?storage/#', '', $existing);
            if (Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }
        }

        $ext = $this->newOgImage->getClientOriginalExtension();
        $path = $this->newOgImage->storeAs('app-settings', 'og-image.'.$ext, 'public');

        // Store the relative storage path; the layout resolves to an absolute URL via asset().
        AppSetting::set('seo_og_image', $path);

        $this->seoOgImage = $path;
        $this->newOgImage = null;
        $this->refreshOgImagePreview();

        $this->dispatch('notify', message: 'Open Graph image uploaded.', type: 'success');
    }

    public function removeOgImage(): void
    {
        $value = AppSetting::get('seo_og_image');
        if ($value && ! preg_match('#^https?://#i', $value)) {
            $path = preg_replace('#^/?storage/#', '', $value);
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }

        AppSetting::set('seo_og_image', null);
        $this->seoOgImage = '';
        $this->currentOgImageUrl = null;
        $this->currentOgImageDimensions = null;

        $this->dispatch('notify', message: 'Open Graph image removed. Layout will fall back to the default headshot.', type: 'success');
    }

    private function refreshOgImagePreview(): void
    {
        $value = AppSetting::get('seo_og_image');
        if (! $value) {
            $this->currentOgImageUrl = null;
            $this->currentOgImageDimensions = null;

            return;
        }

        if (preg_match('#^https?://#i', $value)) {
            // External URL — show as-is, dimensions unknown.
            $this->currentOgImageUrl = $value;
            $this->currentOgImageDimensions = null;

            return;
        }

        $path = preg_replace('#^/?storage/#', '', $value);
        if (Storage::disk('public')->exists($path)) {
            $this->currentOgImageUrl = asset('storage/'.$path);
            $absolute = Storage::disk('public')->path($path);
            $info = @getimagesize($absolute);
            $this->currentOgImageDimensions = ($info && isset($info[0], $info[1]))
                ? "{$info[0]} × {$info[1]} px"
                : null;
        } else {
            $this->currentOgImageUrl = null;
            $this->currentOgImageDimensions = null;
        }
    }

    public function addStaticPage(): void
    {
        $this->sitemapStaticPages[] = ['url' => '', 'changefreq' => 'monthly', 'priority' => '0.5', 'enabled' => true];
    }

    public function removeStaticPage(int $index): void
    {
        array_splice($this->sitemapStaticPages, $index, 1);
    }

    public function resetStaticPages(): void
    {
        $this->sitemapStaticPages = self::DEFAULT_STATIC_PAGES;
        AppSetting::set('seo_sitemap_static_pages', json_encode($this->sitemapStaticPages));
        $this->dispatch('notify', message: 'Static pages reset to defaults.', type: 'success');
    }

    public function saveSeo(): void
    {
        $this->validate([
            'seoTitleSuffix' => 'nullable|string|max:100',
            'seoDefaultDesc' => 'nullable|string|max:320',
            'seoOgImage' => 'nullable|string|max:500',
            'seoTwitterHandle' => 'nullable|string|max:100',
            'seoLinkedinUrl' => ['nullable', 'string', 'max:500', 'regex:/^https?:\/\//i'],
            'seoSchemaJobTitle' => 'nullable|string|max:200',
            'seoSchemaOrgName' => 'nullable|string|max:200',
            'seoGa4Id' => ['nullable', 'string', 'max:50', 'regex:/^G-[A-Z0-9]+$/i'],
            'seoGtmId' => ['nullable', 'string', 'max:50', 'regex:/^GTM-[A-Z0-9]+$/i'],
            'seoGoogleVerify' => 'nullable|string|max:200',
            'seoBingVerify' => 'nullable|string|max:200',
            'robotsDisallowExtra' => 'nullable|string|max:2000',
            'sitemapStaticPages' => 'array',
            'sitemapStaticPages.*.url' => 'nullable|string|max:300',
            'llmsDescription' => 'nullable|string|max:1000',
            'llmsExtra' => 'nullable|string|max:5000',
        ]);

        // Meta
        AppSetting::set('seo_title_suffix', trim($this->seoTitleSuffix));
        AppSetting::set('seo_default_desc', trim($this->seoDefaultDesc));
        AppSetting::set('seo_og_image', trim($this->seoOgImage));

        // Social
        AppSetting::set('seo_twitter_handle', ltrim(trim($this->seoTwitterHandle), '@'));
        AppSetting::set('seo_linkedin_url', trim($this->seoLinkedinUrl));

        // Schema
        AppSetting::set('seo_schema_job_title', trim($this->seoSchemaJobTitle));
        AppSetting::set('seo_schema_org_name', trim($this->seoSchemaOrgName));

        // Analytics
        AppSetting::set('seo_ga4_id', strtoupper(trim($this->seoGa4Id)));
        AppSetting::set('seo_gtm_id', strtoupper(trim($this->seoGtmId)));

        // Verification
        AppSetting::set('seo_google_verify', trim($this->seoGoogleVerify));
        AppSetting::set('seo_bing_verify', trim($this->seoBingVerify));

        // Crawler files
        $pages = array_values(array_filter($this->sitemapStaticPages, fn ($p) => trim($p['url'] ?? '') !== ''));
        $this->sitemapStaticPages = $pages;

        AppSetting::set('seo_robots_disallow_extra', trim($this->robotsDisallowExtra));
        AppSetting::set('seo_sitemap_static_pages', json_encode($pages));
        AppSetting::set('seo_sitemap_blog', $this->sitemapBlog ? '1' : '0');
        AppSetting::set('seo_sitemap_training', $this->sitemapTraining ? '1' : '0');
        AppSetting::set('seo_llms_description', trim($this->llmsDescription));
        AppSetting::set('seo_llms_extra', trim($this->llmsExtra));

        $this->dispatch('notify', message: 'SEO settings saved.', type: 'success');
    }

    public function resetIcons(): void
    {
        foreach (['app_icon', 'favicon'] as $key) {
            $path = AppSetting::get($key);
            if ($path) {
                Storage::disk('public')->delete($path);
            }
            AppSetting::set($key, null);
        }

        $this->currentIconUrl = null;
        $this->currentFaviconUrl = null;
        $this->newAppIcon = null;
        $this->newFavicon = null;

        $this->dispatch('notify', message: 'Icons reset to defaults.', type: 'success');
        $this->js('setTimeout(() => window.location.reload(), 800)');
    }

    // ── Helpers ───────────────────────────────────────────────────────────

    private function resolveStorageUrl(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        return Storage::disk('public')->exists($path)
            ? asset('storage/'.$path)
            : null;
    }

    // ── Render ────────────────────────────────────────────────────────────

    public function render()
    {
        $blogPosts = $this->sitemapBlog
            ? Post::where('status', 'published')->orderByDesc('published_at')->get(['title', 'slug'])
            : collect();

        $trainingClasses = $this->sitemapTraining
            ? Service::where('type', 'training')->where('is_active', true)->orderBy('sort_order')->get(['title', 'slug'])
            : collect();

        $staticCount = count(array_filter($this->sitemapStaticPages, fn ($p) => $p['enabled'] ?? true));
        $totalSitemap = $staticCount + $blogPosts->count() + $trainingClasses->count();

        return view('livewire.admin.app-settings', [
            'previewColors' => AppSetting::resolvedColors(),
            'blogPosts' => $blogPosts,
            'trainingClasses' => $trainingClasses,
            'staticCount' => $staticCount,
            'totalSitemap' => $totalSitemap,
        ]);
    }
}
