<?php

namespace Src\Catalog\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use Src\Catalog\Domain\Repositories\AdminProductRepositoryInterface;
use Src\Catalog\Domain\Repositories\AttributeRepositoryInterface;
use Src\Catalog\Domain\Repositories\GroupRepositoryInterface;
use Src\Catalog\Domain\Repositories\ProductCategoryRepositoryInterface;
use Src\Catalog\Domain\Repositories\ProductRepositoryInterface;
use Src\Catalog\Infrastructure\Persistence\EloquentAdminProductRepository;
use Src\Catalog\Infrastructure\Persistence\EloquentAttributeRepository;
use Src\Catalog\Infrastructure\Persistence\EloquentGroupRepository;
use Src\Catalog\Infrastructure\Persistence\EloquentProductCategoryRepository;
use Src\Catalog\Infrastructure\Persistence\EloquentProductRepository;

/**
 * CatalogServiceProvider service provider.
 */
class CatalogServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ProductRepositoryInterface::class, EloquentProductRepository::class);
        $this->app->bind(ProductCategoryRepositoryInterface::class, EloquentProductCategoryRepository::class);
        $this->app->bind(AttributeRepositoryInterface::class, EloquentAttributeRepository::class);
        $this->app->bind(AdminProductRepositoryInterface::class, EloquentAdminProductRepository::class);
        $this->app->bind(GroupRepositoryInterface::class, EloquentGroupRepository::class);

        // Register public product service
        $this->app->singleton(\Src\Catalog\Application\Services\PublicProductService::class);
    }
}
