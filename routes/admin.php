<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Livewire\Admin\ProfileSettings;
use App\Livewire\Admin\ContactInquiries;
use App\Livewire\Admin\ConsultingServices;
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
// Example placeholder routes for your future CRUD operations
Route::get('/training', function () { return "Training"; })->name('training.index');
Route::get('/papers', function () { return "Papers"; })->name('papers.index');


Route::get('/inquiries', ContactInquiries::class)->name('inquiries');
Route::get('/consulting-services', ConsultingServices::class)->name('services.index');