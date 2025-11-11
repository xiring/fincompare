<?php

namespace Src\Auth\Application\Actions;

use Spatie\Permission\Models\Permission;
use Src\Auth\Application\DTOs\PermissionDTO;
use Src\Auth\Domain\Repositories\PermissionRepositoryInterface;

/**
 * CreatePermissionAction application action.
 */
class CreatePermissionAction
{
    public function __construct(private PermissionRepositoryInterface $repo) {}

    public function execute(PermissionDTO $dto): Permission
    {
        return $this->repo->create($dto);
    }
}
