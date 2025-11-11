<?php

namespace Src\Auth\Application\Actions;

use Src\Auth\Application\DTOs\AdminUserDTO;
use Src\Auth\Domain\Entities\User;
use Src\Auth\Domain\Repositories\AdminUserRepositoryInterface;

/**
 * UpdateAdminUserAction application action.
 */
class UpdateAdminUserAction
{
    public function __construct(private AdminUserRepositoryInterface $repo) {}

    public function execute(User $user, AdminUserDTO $dto): User
    {
        return $this->repo->update($user, $dto);
    }
}
