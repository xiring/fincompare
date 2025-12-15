<?php

namespace Src\Catalog\Domain\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Src\Catalog\Application\DTOs\ProductDTO;
use Src\Catalog\Domain\Entities\Product;
use Src\Shared\Application\Criteria\ListCriteria;

/**
 * AdminProductRepositoryInterface interface.
 */
interface AdminProductRepositoryInterface
{
    public function paginate(ListCriteria $criteria): LengthAwarePaginator;

    public function find(int $id): ?Product;

    public function findBySlug(string $slug): ?Product;

    public function slugExists(string $slug, ?int $excludeId = null): bool;

    public function create(ProductDTO $dto): Product;

    public function update(Product $product, ProductDTO $dto): Product;

    public function delete(Product $product): void;
}
