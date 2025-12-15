<?php

namespace Src\Partners\Application\Actions;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Src\Partners\Domain\Repositories\PartnerRepositoryInterface;
use Src\Shared\Application\Criteria\ListCriteria;

/**
 * ListPartnersAction application action.
 */
class ListPartnersAction
{
    public function __construct(private PartnerRepositoryInterface $repo) {}

    public function execute(ListCriteria $criteria): LengthAwarePaginator
    {
        return $this->repo->paginate($criteria);
    }
}
