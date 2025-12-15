<?php

namespace Src\Catalog\Infrastructure\Persistence;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
use Src\Catalog\Application\DTOs\ProductDTO;
use Src\Catalog\Domain\Entities\Product;
use Src\Catalog\Domain\Repositories\AdminProductRepositoryInterface;
use Src\Shared\Application\Criteria\ListCriteria;

/**
 * EloquentAdminProductRepository repository.
 */
class EloquentAdminProductRepository implements AdminProductRepositoryInterface
{
    public function paginate(ListCriteria $criteria): LengthAwarePaginator
    {
        $sort = in_array(($criteria->getSort() ?? ''), ['id', 'name', 'status', 'created_at']) ? $criteria->getSort() : 'id';
        $dir = $criteria->getDir();
        $filters = $criteria->filters();
        $perPage = $criteria->getPerPage() ?? 20;

        return Product::with(['partner', 'productCategory'])
            ->when($criteria->getSearch(), fn ($q, $qStr) => $q->where('name', 'like', '%'.$qStr.'%'))
            ->when(($filters['product_category_id'] ?? null), fn ($q, $categoryId) => $q->where('product_category_id', $categoryId))
            ->when(($filters['partner_id'] ?? null), fn ($q, $partnerId) => $q->where('partner_id', $partnerId))
            ->orderBy($sort, $dir)
            ->paginate($perPage)->withQueryString();
    }

    public function find(int $id): ?Product
    {
        return Product::with(['partner', 'productCategory', 'attributeValues.attribute'])->find($id);
    }

    public function findBySlug(string $slug): ?Product
    {
        return Product::where('slug', $slug)->first();
    }

    public function slugExists(string $slug, ?int $excludeId = null): bool
    {
        $query = Product::where('slug', $slug);
        if ($excludeId !== null) {
            $query->where('id', '!=', $excludeId);
        }
        return $query->exists();
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
