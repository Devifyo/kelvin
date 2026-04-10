<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Livewire\Admin\ProfileSettings;
use App\Livewire\Admin\ContactInquiries;
use App\Livewire\Admin\ConsultingServices;
use App\Livewire\Admin\TrainingClasses;
use App\Livewire\Admin\BlogPosts;
use App\Livewire\Admin\ManagePapers;
use App\Livewire\Admin\ManagePodcastsWebinars;
use App\Livewire\Admin\VisitorAnalytics;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
| Loaded by bootstrap/app.php.
| Prefix: '/admin' | Name: 'admin.' | Middleware: 'web', 'auth'
*/

// Dashboard Route (URL: /admin, Name: admin.dashboard)
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/profile', ProfileSettings::class)->name('profile');

Route::get('/inquiries', ContactInquiries::class)->name('inquiries');
Route::get('/consulting-services', ConsultingServices::class)->name('services.index');

Route::get('/training-classes', TrainingClasses::class)->name('training.index');

Route::get('/papers', ManagePapers::class)->name('papers.index');

Route::get('/blog-posts', BlogPosts::class)->name('blog.index');

Route::get('/podcasts-webinars', ManagePodcastsWebinars::class)->name('podcasts-webinars')->middleware('maintenance');

Route::get('/visitors', VisitorAnalytics::class)->name('visitors')->middleware('maintenance');


Route::post('/tinymce-upload', function (Request $request) {
    if ($request->hasFile('file')) {
        $path = $request->file('file')->store('blog/content-images', 'public');
        return response()->json(['location' => Storage::url($path)]);
    }
    return response()->json(['error' => 'No file uploaded.'], 400);
})->name('tinymce.upload');