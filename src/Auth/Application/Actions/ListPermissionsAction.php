<?php

namespace Src\Auth\Application\Actions;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Src\Auth\Domain\Repositories\PermissionRepositoryInterface;
use Src\Shared\Application\Criteria\ListCriteria;

/**
 * ListPermissionsAction application action.
 */
class ListPermissionsAction
{
    public function __construct(private PermissionRepositoryInterface $repo) {}

    public function execute(ListCriteria $criteria): LengthAwarePaginator
    {
        return $this->repo->paginate($criteria);
    }
}
