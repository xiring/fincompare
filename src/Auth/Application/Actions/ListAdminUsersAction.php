<?php
namespace Src\Auth\Application\Actions;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Src\Auth\Domain\Repositories\AdminUserRepositoryInterface;

/**
 * ListAdminUsersAction application action.
 *
 * @package Src\Auth\Application\Actions
 */
class ListAdminUsersAction
{
    public function __construct(private AdminUserRepositoryInterface $repo) {}

    public function execute(array $filters = [], int $perPage = 20): LengthAwarePaginator
    {
        return $this->repo->paginate($filters, $perPage);
    }
}


