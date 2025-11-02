<?php
namespace Src\Auth\Application\Actions;

use Src\Auth\Domain\Repositories\PermissionRepositoryInterface;
use Spatie\Permission\Models\Permission;

class DeletePermissionAction
{
    public function __construct(private PermissionRepositoryInterface $repo) {}

    public function execute(Permission $permission): void
    {
        $this->repo->delete($permission);
    }
}


