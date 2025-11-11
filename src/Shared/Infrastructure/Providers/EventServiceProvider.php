<?php

namespace Src\Shared\Infrastructure\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

/**
 * EventServiceProvider service provider.
 */
class EventServiceProvider extends ServiceProvider
{
    protected $listen = [];

    public function boot(): void {}

    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
