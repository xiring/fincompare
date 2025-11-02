<?php
namespace Src\Content\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;

class ContentServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(\Src\Content\Domain\Repositories\BlogPostRepositoryInterface::class, \Src\Content\Infrastructure\Persistence\EloquentBlogPostRepository::class);
        $this->app->bind(\Src\Content\Domain\Repositories\CmsPageRepositoryInterface::class, \Src\Content\Infrastructure\Persistence\EloquentCmsPageRepository::class);
    }
}


