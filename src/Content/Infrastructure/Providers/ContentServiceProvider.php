<?php

namespace Src\Content\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * ContentServiceProvider service provider.
 */
class ContentServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(\Src\Content\Domain\Repositories\BlogPostRepositoryInterface::class, \Src\Content\Infrastructure\Persistence\EloquentBlogPostRepository::class);
        $this->app->bind(\Src\Content\Domain\Repositories\CmsPageRepositoryInterface::class, \Src\Content\Infrastructure\Persistence\EloquentCmsPageRepository::class);
        $this->app->bind(\Src\Content\Domain\Repositories\FaqRepositoryInterface::class, \Src\Content\Infrastructure\Persistence\EloquentFaqRepository::class);

        // Register public blog service
        $this->app->singleton(\Src\Content\Application\Services\PublicBlogService::class);
    }
}
