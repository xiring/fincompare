<?php
namespace Src\Auth\Application\Actions;

use Src\Auth\Application\DTOs\PermissionDTO;
use Src\Auth\Domain\Repositories\PermissionRepositoryInterface;
use Spatie\Permission\Models\Permission;

/**
 * CreatePermissionAction application action.
 *
 * @package Src\Auth\Application\Actions
 */
class CreatePermissionAction
{
    public function __construct(private PermissionRepositoryInterface $repo) {}

    public function execute(PermissionDTO $dto): Permission
    {
        return $this->repo->create($dto);
    }
}


