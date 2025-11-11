<?php

namespace Src\Auth\Application\Actions;

use Spatie\Permission\Models\Permission;
use Src\Auth\Application\DTOs\PermissionDTO;
use Src\Auth\Domain\Repositories\PermissionRepositoryInterface;

/**
 * UpdatePermissionAction application action.
 */
class UpdatePermissionAction
{
    public function __construct(private PermissionRepositoryInterface $repo) {}

    public function execute(Permission $permission, PermissionDTO $dto): Permission
    {
        return $this->repo->update($permission, $dto);
    }
}
