<?php
namespace Src\Auth\Application\Actions;

use Src\Auth\Application\DTOs\RoleDTO;
use Src\Auth\Domain\Repositories\RoleRepositoryInterface;
use Spatie\Permission\Models\Role;

/**
 * CreateRoleAction application action.
 *
 * @package Src\Auth\Application\Actions
 */
class CreateRoleAction
{
    public function __construct(private RoleRepositoryInterface $repo) {}

    public function execute(RoleDTO $dto): Role
    {
        return $this->repo->create($dto);
    }
}


