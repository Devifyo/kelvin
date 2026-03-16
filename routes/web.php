<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ContactController;

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
});

// Contact Logic (Unified)
Route::controller(ContactController::class)->group(function () {
    Route::get('/contact-us', 'show')->name('contact'); // Render the form
    Route::post('/contact-us', 'store')->name('contact.store'); // Process the form
});