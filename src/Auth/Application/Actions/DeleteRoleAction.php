<?php
namespace Src\Auth\Application\Actions;

use Src\Auth\Domain\Repositories\RoleRepositoryInterface;
use Spatie\Permission\Models\Role;

/**
 * DeleteRoleAction application action.
 *
 * @package Src\Auth\Application\Actions
 */
class DeleteRoleAction
{
    public function __construct(private RoleRepositoryInterface $repo) {}

    public function execute(Role $role): void
    {
        $this->repo->delete($role);
    }
}


