<?php
namespace Src\Leads\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use Src\Leads\Domain\Repositories\LeadRepositoryInterface;
use Src\Leads\Infrastructure\Persistence\EloquentLeadRepository;

/**
 * LeadsServiceProvider service provider.
 *
 * @package Src\Leads\Infrastructure\Providers
 */
class LeadsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(LeadRepositoryInterface::class, EloquentLeadRepository::class);
    }
}


