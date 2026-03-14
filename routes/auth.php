<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;

// The name("auth.") here adds "auth." to the start of everything inside
Route::controller(AuthController::class)->name("auth.")->group(function () {
    
    // Change this from "auth.login" to just "login"
    // The final registered name will now be "auth.login"
    Route::any("/login", 'login')->name("login");
    
});