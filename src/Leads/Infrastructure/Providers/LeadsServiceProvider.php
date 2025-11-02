<?php
namespace Src\Leads\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use Src\Leads\Domain\Repositories\LeadRepositoryInterface;
use Src\Leads\Infrastructure\Persistence\EloquentLeadRepository;

class LeadsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(LeadRepositoryInterface::class, EloquentLeadRepository::class);
    }
}


