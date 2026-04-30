<?php

namespace App\Providers;

use App\Models\AppSetting;
use App\Models\Paper;
use App\Models\Post;
use App\Models\Service;
use App\Services\SeoGenerator;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use SocialiteProviders\Manager\SocialiteWasCalled;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        URL::forceScheme('https');

        Event::listen(SocialiteWasCalled::class, [
            \SocialiteProviders\Apple\AppleExtendSocialite::class, 'handle',
        ]);

        $this->registerSeoHooks();
    }

    private function registerSeoHooks(): void
    {
        $sitemapAndLlms = function () {
            SeoGenerator::generateSitemap();
            SeoGenerator::generateLlms();
        };

        // Blog posts affect sitemap + llms
        Post::saved($sitemapAndLlms);
        Post::deleted($sitemapAndLlms);

        // Training services affect sitemap + llms
        Service::saved($sitemapAndLlms);
        Service::deleted($sitemapAndLlms);

        // Papers affect llms only
        Paper::saved(fn()   => SeoGenerator::generateLlms());
        Paper::deleted(fn() => SeoGenerator::generateLlms());

        // SEO settings affect all three files
        AppSetting::saved(function (AppSetting $setting) {
            if (str_starts_with($setting->key, 'seo_') || $setting->key === 'app_name') {
                SeoGenerator::generateAll();
            }
        });
    }
}
