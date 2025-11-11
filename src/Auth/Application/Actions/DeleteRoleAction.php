<?php

namespace Src\Auth\Application\Actions;

use Spatie\Permission\Models\Role;
use Src\Auth\Domain\Repositories\RoleRepositoryInterface;

/**
 * DeleteRoleAction application action.
 */
class DeleteRoleAction
{
    public function __construct(private RoleRepositoryInterface $repo) {}

    public function execute(Role $role): void
    {
        $this->repo->delete($role);
    }
}
