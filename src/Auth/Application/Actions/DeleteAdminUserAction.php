<?php

namespace Src\Auth\Application\Actions;

use Src\Auth\Domain\Entities\User;
use Src\Auth\Domain\Repositories\AdminUserRepositoryInterface;

/**
 * DeleteAdminUserAction application action.
 */
class DeleteAdminUserAction
{
    public function __construct(private AdminUserRepositoryInterface $repo) {}

    public function execute(User $user): void
    {
        $this->repo->delete($user);
    }
}
