<?php

namespace Src\Catalog\Application\Actions;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Src\Catalog\Domain\Repositories\AdminProductRepositoryInterface;
use Src\Shared\Application\Criteria\ListCriteria;

/**
 * ListProductsAction application action.
 */
class ListProductsAction
{
    public function __construct(private AdminProductRepositoryInterface $repo) {}

    public function execute(ListCriteria $criteria): LengthAwarePaginator
    {
        return $this->repo->paginate($criteria);
    }
}
