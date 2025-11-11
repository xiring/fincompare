<?php

use Illuminate\Support\Facades\Route;
use Src\Auth\Presentation\Controllers\LoginController;
use Src\Auth\Presentation\Controllers\SocialLoginController;

Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'create'])
        ->name('login');

    Route::post('login', [LoginController::class, 'store']);

    // Social login
    Route::get('auth/{provider}/redirect', [SocialLoginController::class, 'redirect'])
        ->whereIn('provider', ['google', 'github'])
        ->name('oauth.redirect');
    Route::get('auth/{provider}/callback', [SocialLoginController::class, 'callback'])
        ->whereIn('provider', ['google', 'github'])
        ->name('oauth.callback');
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [LoginController::class, 'destroy'])
        ->name('logout');
});
