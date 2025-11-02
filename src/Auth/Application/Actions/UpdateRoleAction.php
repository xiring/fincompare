<?php
namespace Src\Auth\Application\Actions;

use Src\Auth\Application\DTOs\RoleDTO;
use Src\Auth\Domain\Repositories\RoleRepositoryInterface;
use Spatie\Permission\Models\Role;

class UpdateRoleAction
{
    public function __construct(private RoleRepositoryInterface $repo) {}

    public function execute(Role $role, RoleDTO $dto): Role
    {
        return $this->repo->update($role, $dto);
    }
}


