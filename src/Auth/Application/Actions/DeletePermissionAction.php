<?php

namespace Src\Auth\Application\Actions;

use Spatie\Permission\Models\Permission;
use Src\Auth\Domain\Repositories\PermissionRepositoryInterface;

/**
 * DeletePermissionAction application action.
 */
class DeletePermissionAction
{
    public function __construct(private PermissionRepositoryInterface $repo) {}

    public function execute(Permission $permission): void
    {
        $this->repo->delete($permission);
    }
}
