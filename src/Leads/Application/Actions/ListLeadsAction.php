<?php

namespace Src\Leads\Application\Actions;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Src\Leads\Domain\Repositories\LeadRepositoryInterface;
use Src\Shared\Application\Criteria\ListCriteria;

/**
 * ListLeadsAction application action.
 */
class ListLeadsAction
{
    public function __construct(private LeadRepositoryInterface $repo) {}

    public function execute(ListCriteria $criteria): LengthAwarePaginator
    {
        return $this->repo->paginate($criteria);
    }
}
