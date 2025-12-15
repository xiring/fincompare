<?php

namespace Src\Catalog\Infrastructure\Persistence;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
use Src\Catalog\Application\DTOs\ProductCategoryDTO;
use Src\Catalog\Domain\Entities\ProductCategory;
use Src\Catalog\Domain\Repositories\ProductCategoryRepositoryInterface;
use Src\Shared\Application\Criteria\ListCriteria;

/**
 * EloquentProductCategoryRepository repository.
 */
class EloquentProductCategoryRepository implements ProductCategoryRepositoryInterface
{
    public function paginate(ListCriteria $criteria): LengthAwarePaginator
    {
        $sort = in_array(($criteria->getSort() ?? ''), ['id', 'name', 'is_active', 'created_at']) ? $criteria->getSort() : 'id';
        $dir = $criteria->getDir();
        $filters = $criteria->filters();
        $perPage = $criteria->getPerPage() ?? 20;

        return ProductCategory::query()
            ->with(['group'])
            ->when($criteria->getSearch(), fn ($q, $qStr) => $q->where('name', 'like', '%'.$qStr.'%'))
            ->when(($filters['group_id'] ?? null), fn ($q, $groupId) => $q->where('group_id', $groupId))
            ->orderBy($sort, $dir)
            ->paginate($perPage)->withQueryString();
    }

    public function find(int $id): ?ProductCategory
    {
        return ProductCategory::with(['group', 'preForm', 'postForm'])->find($id);
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
