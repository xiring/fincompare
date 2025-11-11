<?php

namespace Src\Catalog\Infrastructure\Persistence;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
use Src\Catalog\Application\DTOs\ProductDTO;
use Src\Catalog\Domain\Entities\Product;
use Src\Catalog\Domain\Repositories\AdminProductRepositoryInterface;

/**
 * EloquentAdminProductRepository repository.
 */
class EloquentAdminProductRepository implements AdminProductRepositoryInterface
{
    public function paginate(array $filters = [], int $perPage = 20): LengthAwarePaginator
    {
        $sort = in_array(($filters['sort'] ?? ''), ['id', 'name', 'status', 'created_at']) ? $filters['sort'] : 'id';
        $dir = strtolower($filters['dir'] ?? 'desc') === 'asc' ? 'asc' : 'desc';

        return Product::with(['partner', 'productCategory'])
            ->when(($filters['q'] ?? null), fn ($q, $qStr) => $q->where('name', 'like', '%'.$qStr.'%'))
            ->orderBy($sort, $dir)
            ->paginate($perPage)->withQueryString();
    }

    public function find(int $id): ?Product
    {
        return Product::with(['partner', 'productCategory', 'attributeValues.attribute'])->find($id);
    }

    public function create(ProductDTO $dto): Product
    {
        $data = $dto->toArray();
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        return Product::create($data);
    }

    public function update(Product $product, ProductDTO $dto): Product
    {
        $data = $dto->toArray();
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }
        $product->update($data);

        return $product;
    }

    public function delete(Product $product): void
    {
        $product->delete();
    }
}
