<?php

namespace Src\Auth\Infrastructure\Persistence;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Spatie\Permission\Models\Permission;
use Src\Auth\Application\DTOs\PermissionDTO;
use Src\Auth\Domain\Repositories\PermissionRepositoryInterface;
use Src\Shared\Application\Criteria\ListCriteria;

/**
 * SpatiePermissionRepository repository.
 */
class SpatiePermissionRepository implements PermissionRepositoryInterface
{
    public function paginate(ListCriteria $criteria): LengthAwarePaginator
    {
        $sort = in_array(($criteria->getSort() ?? ''), ['id', 'name', 'created_at']) ? $criteria->getSort() : 'id';
        $dir = $criteria->getDir();
        $perPage = $criteria->getPerPage() ?? 20;

        return Permission::query()
            ->when($criteria->getSearch(), fn ($q, $qStr) => $q->where('name', 'like', '%'.$qStr.'%'))
            ->orderBy($sort, $dir)
            ->paginate($perPage)->withQueryString();
    }

    public function find(int $id): ?Permission
    {
        return Permission::find($id);
    }

    public function create(PermissionDTO $dto): Permission
    {
        return Permission::create(['name' => $dto->name]);
    }

    public function update(Permission $permission, PermissionDTO $dto): Permission
    {
        $permission->update(['name' => $dto->name]);

        return $permission;
    }

    public function delete(Permission $permission): void
    {
        $permission->delete();
    }
}
