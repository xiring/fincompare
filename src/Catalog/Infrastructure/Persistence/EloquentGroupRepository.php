<?php

namespace Src\Catalog\Infrastructure\Persistence;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
use Src\Catalog\Application\DTOs\GroupDTO;
use Src\Catalog\Domain\Entities\Group;
use Src\Catalog\Domain\Repositories\GroupRepositoryInterface;
use Src\Shared\Application\Criteria\ListCriteria;

/**
 * EloquentGroupRepository repository.
 */
class EloquentGroupRepository implements GroupRepositoryInterface
{
    public function paginate(ListCriteria $criteria): LengthAwarePaginator
    {
        $sort = in_array(($criteria->getSort() ?? ''), ['id', 'name', 'is_active', 'sort_order', 'created_at']) ? $criteria->getSort() : 'id';
        $dir = $criteria->getDir();
        $filters = $criteria->filters();
        $perPage = $criteria->getPerPage() ?? 20;

        return Group::query()
            ->when($criteria->getSearch(), fn ($q, $qStr) => $q->where('name', 'like', '%'.$qStr.'%'))
            ->orderBy($sort, $dir)
            ->paginate($perPage)
            ->withQueryString();
    }

    public function find(int $id): ?Group
    {
        return Group::find($id);
    }

    public function create(GroupDTO $dto): Group
    {
        $data = $dto->toArray();
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        return Group::create($data);
    }

    public function update(Group $group, GroupDTO $dto): Group
    {
        $data = $dto->toArray();
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $group->update($data);

        return $group;
    }

    public function delete(Group $group): void
    {
        $group->delete();
    }
}


