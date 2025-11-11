<?php
namespace Src\Auth\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use Src\Auth\Domain\Repositories\AdminUserRepositoryInterface;
use Src\Auth\Domain\Repositories\PermissionRepositoryInterface;
use Src\Auth\Domain\Repositories\RoleRepositoryInterface;
use Src\Auth\Infrastructure\Persistence\EloquentAdminUserRepository;
use Src\Auth\Infrastructure\Persistence\SpatiePermissionRepository;
use Src\Auth\Infrastructure\Persistence\SpatieRoleRepository;

/**
 * AuthModuleServiceProvider service provider.
 *
 * @package Src\Auth\Infrastructure\Providers
 */
class AuthModuleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(AdminUserRepositoryInterface::class, EloquentAdminUserRepository::class);
        $this->app->bind(PermissionRepositoryInterface::class, SpatiePermissionRepository::class);
        $this->app->bind(RoleRepositoryInterface::class, SpatieRoleRepository::class);
    }
}


