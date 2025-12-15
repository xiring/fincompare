<?php

namespace Src\Auth\Application\Actions;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Src\Auth\Domain\Repositories\AdminUserRepositoryInterface;
use Src\Shared\Application\Criteria\ListCriteria;

/**
 * ListAdminUsersAction application action.
 */
class ListAdminUsersAction
{
    public function __construct(private AdminUserRepositoryInterface $repo) {}

    public function execute(ListCriteria $criteria): LengthAwarePaginator
    {
        return $this->repo->paginate($criteria);
    }
}
