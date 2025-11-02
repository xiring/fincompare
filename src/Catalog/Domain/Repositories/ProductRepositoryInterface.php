<?php
namespace Src\Catalog\Domain\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProductFilters {
    public function __construct(public int $categoryId, public array $filters = [], public int $perPage = 20) {}
}

interface ProductRepositoryInterface {
    public function filtered(ProductFilters $filters): LengthAwarePaginator;
}
