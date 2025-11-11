<?php
namespace Src\Auth\Application\Actions;

use Src\Auth\Domain\Repositories\AdminUserRepositoryInterface;
use Src\Auth\Domain\Entities\User;

/**
 * DeleteAdminUserAction application action.
 *
 * @package Src\Auth\Application\Actions
 */
class DeleteAdminUserAction
{
    public function __construct(private AdminUserRepositoryInterface $repo) {}

    public function execute(User $user): void
    {
        $this->repo->delete($user);
    }
}


