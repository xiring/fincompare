<?php
namespace Src\Partners\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use Src\Partners\Domain\Repositories\PartnerRepositoryInterface;
use Src\Partners\Infrastructure\Persistence\EloquentPartnerRepository;

/**
 * PartnersServiceProvider service provider.
 *
 * @package Src\Partners\Infrastructure\Providers
 */
class PartnersServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(PartnerRepositoryInterface::class, EloquentPartnerRepository::class);
    }
}


