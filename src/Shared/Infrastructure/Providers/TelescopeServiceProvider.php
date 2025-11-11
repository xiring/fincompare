<?php

namespace Src\Shared\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Telescope\Telescope;

/**
 * TelescopeServiceProvider service provider.
 */
class TelescopeServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Ensure Telescope's own service provider is registered in all environments
        if (class_exists(\Laravel\Telescope\TelescopeServiceProvider::class)) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
        }
    }

    public function boot(): void
    {
        if (! class_exists(Telescope::class)) {
            return;
        }

        Telescope::auth(function ($request) {
            $user = $request->user();

            return $user && method_exists($user, 'hasRole') && $user->hasRole('admin');
        });
    }
}
