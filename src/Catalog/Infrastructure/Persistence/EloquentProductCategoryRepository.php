<?php

namespace Src\Catalog\Infrastructure\Persistence;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
use Src\Catalog\Application\DTOs\ProductCategoryDTO;
use Src\Catalog\Domain\Entities\ProductCategory;
use Src\Catalog\Domain\Repositories\ProductCategoryRepositoryInterface;

/**
 * EloquentProductCategoryRepository repository.
 */
class EloquentProductCategoryRepository implements ProductCategoryRepositoryInterface
{
    public function paginate(array $filters = [], int $perPage = 20): LengthAwarePaginator
    {
        $sort = in_array(($filters['sort'] ?? ''), ['id', 'name', 'is_active', 'created_at']) ? $filters['sort'] : 'id';
        $dir = strtolower($filters['dir'] ?? 'asc') === 'desc' ? 'desc' : 'asc';

        return ProductCategory::query()
            ->when(($filters['q'] ?? null), fn ($q, $qStr) => $q->where('name', 'like', '%'.$qStr.'%'))
            ->orderBy($sort, $dir)
            ->paginate($perPage)->withQueryString();
    }

    public function find(int $id): ?ProductCategory
    {
        return ProductCategory::with(['preForm', 'postForm'])->find($id);
    }

    public function create(ProductCategoryDTO $dto): ProductCategory
    {
        $data = $dto->toArray();
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        return ProductCategory::create($data);
    }

    public function update(ProductCategory $category, ProductCategoryDTO $dto): ProductCategory
    {
        $data = $dto->toArray();
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }
        $category->update($data);

        return $category;
    }

    public function delete(ProductCategory $category): void
    {
        $category->delete();
    }
}
