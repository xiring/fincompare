<?php

namespace Src\Auth\Application\Actions;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Src\Auth\Domain\Repositories\RoleRepositoryInterface;
use Src\Shared\Application\Criteria\ListCriteria;

/**
 * ListRolesAction application action.
 */
class ListRolesAction
{
    public function __construct(private RoleRepositoryInterface $repo) {}

    public function execute(ListCriteria $criteria): LengthAwarePaginator
    {
        return $this->repo->paginate($criteria);
    }
}
