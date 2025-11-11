<?php

namespace Src\Settings\Infrastructure\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Src\Settings\Domain\Repositories\SiteSettingRepositoryInterface;
use Src\Settings\Infrastructure\Persistence\EloquentSiteSettingRepository;
use Src\Settings\Presentation\View\Composers\SiteSettingComposer;

/**
 * SettingsServiceProvider service provider.
 */
class SettingsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(SiteSettingRepositoryInterface::class, EloquentSiteSettingRepository::class);
    }

    public function boot(): void
    {
        View::composer('*', SiteSettingComposer::class);
    }
}
