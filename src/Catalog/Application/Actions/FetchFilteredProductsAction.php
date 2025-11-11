<?php

namespace Src\Catalog\Application\Actions;

use Src\Catalog\Domain\Repositories\ProductFilters;
use Src\Catalog\Domain\Repositories\ProductRepositoryInterface;

/**
 * FetchFilteredProductsAction application action.
 */
class FetchFilteredProductsAction
{
    public function __construct(private ProductRepositoryInterface $repo) {}

    /**
     * Handle Execute.
     *
     * @return mixed
     */
    public function execute(int $categoryId, array $query, int $perPage = 20)
    {
        return $this->repo->filtered(new ProductFilters($categoryId, $query, $perPage));
    }
}
