<?php

namespace Src\Catalog\Application\Actions;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Src\Catalog\Domain\Repositories\GroupRepositoryInterface;
use Src\Shared\Application\Criteria\ListCriteria;

/**
 * ListGroupsAction application action.
 */
class ListGroupsAction
{
    public function __construct(private GroupRepositoryInterface $repo) {}

    public function execute(ListCriteria $criteria): LengthAwarePaginator
    {
        return $this->repo->paginate($criteria);
    }
}


