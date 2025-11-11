<?php
namespace Src\Auth\Application\Actions;

use Src\Auth\Application\DTOs\AdminUserDTO;
use Src\Auth\Domain\Repositories\AdminUserRepositoryInterface;
use Src\Auth\Domain\Entities\User;

/**
 * CreateAdminUserAction application action.
 *
 * @package Src\Auth\Application\Actions
 */
class CreateAdminUserAction
{
    public function __construct(private AdminUserRepositoryInterface $repo) {}

    public function execute(AdminUserDTO $dto): User
    {
        return $this->repo->create($dto);
    }
}


