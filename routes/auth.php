<?php

use Src\Auth\Presentation\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'create'])
                ->name('login');

    Route::post('login', [LoginController::class, 'store']);

    // Social login
    Route::get('auth/{provider}/redirect', [\Src\Auth\Presentation\Controllers\SocialLoginController::class,'redirect'])
        ->whereIn('provider', ['google','github'])
        ->name('oauth.redirect');
    Route::get('auth/{provider}/callback', [\Src\Auth\Presentation\Controllers\SocialLoginController::class,'callback'])
        ->whereIn('provider', ['google','github'])
        ->name('oauth.callback');
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [LoginController::class, 'destroy'])
                ->name('logout');
});
