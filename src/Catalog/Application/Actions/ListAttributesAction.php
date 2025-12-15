<?php

namespace Src\Catalog\Application\Actions;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Src\Catalog\Domain\Repositories\AttributeRepositoryInterface;
use Src\Shared\Application\Criteria\ListCriteria;

/**
 * ListAttributesAction application action.
 */
class ListAttributesAction
{
    public function __construct(private AttributeRepositoryInterface $repo) {}

    public function execute(ListCriteria $criteria): LengthAwarePaginator
    {
        return $this->repo->paginate($criteria);
    }
}
