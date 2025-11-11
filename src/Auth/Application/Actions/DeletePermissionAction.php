<?php
namespace Src\Auth\Application\Actions;

use Src\Auth\Domain\Repositories\PermissionRepositoryInterface;
use Spatie\Permission\Models\Permission;

/**
 * DeletePermissionAction application action.
 *
 * @package Src\Auth\Application\Actions
 */
class DeletePermissionAction
{
    public function __construct(private PermissionRepositoryInterface $repo) {}

    public function execute(Permission $permission): void
    {
        $this->repo->delete($permission);
    }
}


