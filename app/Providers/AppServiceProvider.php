<?php

namespace App\Providers;

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
    }
}
