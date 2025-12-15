<?php

namespace Src\Catalog\Application\Actions;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Src\Catalog\Domain\Repositories\ProductCategoryRepositoryInterface;
use Src\Shared\Application\Criteria\ListCriteria;

/**
 * ListProductCategoriesAction application action.
 */
class ListProductCategoriesAction
{
    public function __construct(private ProductCategoryRepositoryInterface $repo) {}

    public function execute(ListCriteria $criteria): LengthAwarePaginator
    {
        return $this->repo->paginate($criteria);
    }
}
