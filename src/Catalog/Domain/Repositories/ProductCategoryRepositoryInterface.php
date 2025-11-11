<?php

namespace Src\Catalog\Domain\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Src\Catalog\Application\DTOs\ProductCategoryDTO;
use Src\Catalog\Domain\Entities\ProductCategory;

/**
 * ProductCategoryRepositoryInterface interface.
 */
interface ProductCategoryRepositoryInterface
{
    public function paginate(array $filters = [], int $perPage = 20): LengthAwarePaginator;

    public function find(int $id): ?ProductCategory;

    public function create(ProductCategoryDTO $dto): ProductCategory;

    public function update(ProductCategory $category, ProductCategoryDTO $dto): ProductCategory;

    public function delete(ProductCategory $category): void;
}
