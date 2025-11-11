<?php
namespace Src\Catalog\Domain\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * ProductFilters class.
 *
 * @package Src\Catalog\Domain\Repositories
 */
class ProductFilters {
    public function __construct(public int $categoryId, public array $filters = [], public int $perPage = 20) {}
}

/**
 * ProductRepositoryInterface interface.
 *
 * @package Src\Catalog\Domain\Repositories
 */
interface ProductRepositoryInterface {
    public function filtered(ProductFilters $filters): LengthAwarePaginator;
}
