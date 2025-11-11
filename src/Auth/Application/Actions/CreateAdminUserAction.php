<?php

namespace Src\Auth\Application\Actions;

use Src\Auth\Application\DTOs\AdminUserDTO;
use Src\Auth\Domain\Entities\User;
use Src\Auth\Domain\Repositories\AdminUserRepositoryInterface;

/**
 * CreateAdminUserAction application action.
 */
class CreateAdminUserAction
{
    public function __construct(private AdminUserRepositoryInterface $repo) {}

    public function execute(AdminUserDTO $dto): User
    {
        return $this->repo->create($dto);
    }
}
