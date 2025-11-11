<?php

namespace Src\Catalog\Application\Actions;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Src\Catalog\Domain\Repositories\ProductCategoryRepositoryInterface;

/**
 * ListProductCategoriesAction application action.
 */
class ListProductCategoriesAction
{
    public function __construct(private ProductCategoryRepositoryInterface $repo) {}

    public function execute(array $filters = [], int $perPage = 20): LengthAwarePaginator
    {
        return $this->repo->paginate($filters, $perPage);
    }
}
