<?php

namespace App\Providers;

use App\Models\AppSetting;
use App\Models\Paper;
use App\Models\Post;
use App\Models\Service;
use App\Services\SeoGenerator;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\RateLimiter;
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

        $this->registerRateLimiters();
        $this->registerSeoHooks();
    }

    /**
     * Named rate limiters for public-facing forms.
     */
    private function registerRateLimiters(): void
    {
        // Contact form: cap submissions per IP to blunt spam floods and
        // brute-forcing of the captcha. Two tiers — a short burst guard and a
        // longer-window cap — and a friendly redirect-back instead of a raw 429.
        RateLimiter::for('contact', function (Request $request) {
            $tooMany = fn () => back()
                ->withInput($request->except(['captcha', '_captcha_token', '_captcha_hp', '_token']))
                ->with('error', 'You\'re sending messages a little too quickly. Please wait a minute and try again.');

            return [
                Limit::perMinute(5)->by('contact-min:' . $request->ip())->response($tooMany),
                Limit::perHour(20)->by('contact-hr:' . $request->ip())->response($tooMany),
            ];
        });
    }

    private function registerSeoHooks(): void
    {
        // These hooks write real files into public/. Left enabled under
        // `artisan test`, fixture slugs and the local APP_URL get baked into the
        // shipped sitemap.xml / llms.txt, which then get committed and deployed.
        //
        // Note: environment detection is NOT reliable here — under `artisan test`
        // this provider boots reporting environment "local" with
        // runningUnitTests() === false. phpunit.xml sets SEO_AUTOGENERATE=false
        // explicitly instead. Regenerate deliberately with `php artisan seo:generate`.
        if (! config('seo.autogenerate', true)) {
            return;
        }

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
