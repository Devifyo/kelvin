<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;

Route::controller(AuthController::class)->name("auth.")->group(function () {

    Route::middleware('redirect.logged.in')->group(function(){
        Route::any("/login", 'login')->name("login");
        Route::post("/logout", 'logout')->name("logout");
    });

});