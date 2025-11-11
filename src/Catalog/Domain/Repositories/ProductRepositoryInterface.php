<?php

namespace Src\Catalog\Domain\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * ProductFilters class.
 */
class ProductFilters
{
    public function __construct(public int $categoryId, public array $filters = [], public int $perPage = 20) {}
}

/**
 * ProductRepositoryInterface interface.
 */
interface ProductRepositoryInterface
{
    public function filtered(ProductFilters $filters): LengthAwarePaginator;
}
