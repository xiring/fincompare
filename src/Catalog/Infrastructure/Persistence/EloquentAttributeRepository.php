<?php

namespace Src\Catalog\Infrastructure\Persistence;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Src\Catalog\Application\DTOs\AttributeDTO;
use Src\Catalog\Domain\Entities\Attribute;
use Src\Catalog\Domain\Repositories\AttributeRepositoryInterface;

/**
 * EloquentAttributeRepository repository.
 */
class EloquentAttributeRepository implements AttributeRepositoryInterface
{
    public function paginate(array $filters = [], int $perPage = 20): LengthAwarePaginator
    {
        $sort = $filters['sort'] ?? 'sort_order';
        $dir = strtolower($filters['dir'] ?? 'asc') === 'desc' ? 'desc' : 'asc';

        return Attribute::query()
            ->with('productCategory')
            ->when(($filters['product_category_id'] ?? null), fn ($q, $cid) => $q->where('product_category_id', $cid))
            ->when(($filters['q'] ?? null), fn ($q, $qStr) => $q->where('name', 'like', '%'.$qStr.'%'))
            ->when($sort === 'product_category_id', fn ($q) => $q->orderBy('product_category_id', $dir)->orderBy('sort_order'))
            ->when($sort !== 'product_category_id', fn ($q) => $q->orderBy($sort, $dir))
            ->paginate($perPage);
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
