<?php
namespace Src\Catalog\Domain\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Src\Catalog\Application\DTOs\ProductDTO;
use Src\Catalog\Domain\Entities\Product;

/**
 * AdminProductRepositoryInterface interface.
 *
 * @package Src\Catalog\Domain\Repositories
 */
interface AdminProductRepositoryInterface
{
    public function paginate(array $filters = [], int $perPage = 20): LengthAwarePaginator;
    public function find(int $id): ?Product;
    public function create(ProductDTO $dto): Product;
    public function update(Product $product, ProductDTO $dto): Product;
    public function delete(Product $product): void;
}


