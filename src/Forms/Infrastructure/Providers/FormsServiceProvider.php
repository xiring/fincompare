<?php

namespace Src\Forms\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use Src\Forms\Domain\Repositories\FormInputRepositoryInterface;
use Src\Forms\Domain\Repositories\FormRepositoryInterface;
use Src\Forms\Infrastructure\Persistence\EloquentFormInputRepository;
use Src\Forms\Infrastructure\Persistence\EloquentFormRepository;

/**
 * FormsServiceProvider service provider.
 */
class FormsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(FormRepositoryInterface::class, EloquentFormRepository::class);
        $this->app->bind(FormInputRepositoryInterface::class, EloquentFormInputRepository::class);
    }
}

