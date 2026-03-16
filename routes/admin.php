<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Livewire\Admin\ProfileSettings;
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
Route::get('/services', function () { return "Services"; })->name('services.index');
Route::get('/training', function () { return "Training"; })->name('training.index');
Route::get('/papers', function () { return "Papers"; })->name('papers.index');
