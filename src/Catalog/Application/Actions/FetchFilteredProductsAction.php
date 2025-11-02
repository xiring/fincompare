<?php
namespace Src\Catalog\Application\Actions;

use Src\Catalog\Domain\Repositories\ProductFilters;
use Src\Catalog\Domain\Repositories\ProductRepositoryInterface;

class FetchFilteredProductsAction
{
    public function __construct(private ProductRepositoryInterface $repo) {}

    public function execute(int $categoryId, array $query, int $perPage = 20)
    {
        return $this->repo->filtered(new ProductFilters($categoryId, $query, $perPage));
    }
}
