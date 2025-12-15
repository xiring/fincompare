<?php

namespace Src\Catalog\Infrastructure\Persistence;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Src\Catalog\Application\DTOs\AttributeDTO;
use Src\Catalog\Domain\Entities\Attribute;
use Src\Catalog\Domain\Repositories\AttributeRepositoryInterface;
use Src\Shared\Application\Criteria\ListCriteria;

/**
 * EloquentAttributeRepository repository.
 */
class EloquentAttributeRepository implements AttributeRepositoryInterface
{
    public function paginate(ListCriteria $criteria): LengthAwarePaginator
    {
        $sort = in_array(($criteria->getSort() ?? ''), ['id', 'name', 'sort_order', 'product_category_id']) ? $criteria->getSort() : 'id';
        $dir = $criteria->getDir();
        $filters = $criteria->filters();
        $perPage = $criteria->getPerPage() ?? 20;

        return Attribute::query()
            ->with('productCategory')
            ->when(($filters['product_category_id'] ?? null), fn ($q, $cid) => $q->where('product_category_id', $cid))
            ->when($criteria->getSearch(), fn ($q, $qStr) => $q->where('name', 'like', '%'.$qStr.'%'))
            ->when($sort === 'product_category_id', fn ($q) => $q->orderBy('product_category_id', $dir)->orderBy('sort_order'))
            ->when($sort !== 'product_category_id', fn ($q) => $q->orderBy($sort, $dir))
            ->paginate($perPage)->withQueryString();
    }

    public function find(int $id): ?Attribute
    {
        return Attribute::find($id);
    }

    public function create(AttributeDTO $dto): Attribute
    {
        $data = $dto->toArray();
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        return Attribute::create($data);
    }

    public function update(Attribute $attribute, AttributeDTO $dto): Attribute
    {
        $data = $dto->toArray();
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }
        $attribute->update($data);

        return $attribute;
    }

    public function delete(Attribute $attribute): void
    {
        $attribute->delete();
    }

    public function byCategory(int $productCategoryId): Collection
    {
        return Attribute::where('product_category_id', $productCategoryId)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
    }
}
