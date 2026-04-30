<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\SeoController;
use App\Http\Controllers\VisaController;

/*
|--------------------------------------------------------------------------
| SEO Optimized Frontend Routes
|--------------------------------------------------------------------------
*/

Route::controller(PageController::class)->group(function () {
    // Home
    Route::get('/', 'home')->name('home');
    
    // About
    Route::get('/about-kevin-thompson', 'about')->name('about');
    
    // Services
    Route::get('/agile-consulting-services', 'services')->name('services.training');
    
    // Training (Main listing)
    Route::get('/agile-training-classes/{slug?}', 'training')->name('training');
    
    // Papers & Presentations (Library)
    Route::get('/agile-hardware-papers-and-presentations', 'papers')->name('papers');

    Route::get('/agile-insights-blog', 'blog')->name('blog');
    Route::get('/agile-insights-blog/{slug}', 'showBlog')->name('blog.show');
    Route::get('/podcasts-webinars', 'podcastsWebinars')->name('podcasts-webinars');
});

// Contact Logic (Unified)
Route::controller(ContactController::class)->group(function () {
    Route::get('/contact-us', 'show')->name('contact'); // Render the form
    Route::post('/contact-us', 'store')->name('contact.store'); // Process the form
});


Route::get('/visa/eater-sunday', [VisaController::class, 'showEasterSunday'])->name('visa.eater-sunday');

// SEO files — no session/cookie middleware so crawlers get clean public responses
Route::withoutMiddleware([
    \Illuminate\Session\Middleware\StartSession::class,
    \Illuminate\View\Middleware\ShareErrorsFromSession::class,
    \Illuminate\Cookie\Middleware\EncryptCookies::class,
    \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
    \Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class,
    \App\Http\Middleware\TrackVisitor::class,
])->group(function () {
    Route::get('/robots.txt', [SeoController::class, 'robots'])->name('seo.robots');
    Route::get('/sitemap.xml', [SeoController::class, 'sitemap'])->name('seo.sitemap');
    Route::get('/llms.txt',    [SeoController::class, 'llms'])->name('seo.llms');
});