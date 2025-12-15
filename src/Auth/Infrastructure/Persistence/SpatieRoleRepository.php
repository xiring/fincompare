<?php

namespace Src\Auth\Infrastructure\Persistence;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Spatie\Permission\Models\Role;
use Src\Auth\Application\DTOs\RoleDTO;
use Src\Auth\Domain\Repositories\RoleRepositoryInterface;
use Src\Shared\Application\Criteria\ListCriteria;

/**
 * SpatieRoleRepository repository.
 */
class SpatieRoleRepository implements RoleRepositoryInterface
{
    public function paginate(ListCriteria $criteria): LengthAwarePaginator
    {
        $sort = in_array(($criteria->getSort() ?? ''), ['id', 'name', 'created_at']) ? $criteria->getSort() : 'id';
        $dir = $criteria->getDir();
        $perPage = $criteria->getPerPage() ?? 20;

        return Role::query()
            ->when($criteria->getSearch(), fn ($q, $qStr) => $q->where('name', 'like', '%'.$qStr.'%'))
            ->orderBy($sort, $dir)
            ->paginate($perPage)->withQueryString();
    }

    public function find(int $id): ?Role
    {
        return Role::find($id);
    }

    public function create(RoleDTO $dto): Role
    {
        $role = Role::create(['name' => $dto->name]);
        if (! empty($dto->permissions)) {
            $role->syncPermissions($dto->permissions);
        }

        return $role;
    }

    public function update(Role $role, RoleDTO $dto): Role
    {
        $role->update(['name' => $dto->name]);
        $role->syncPermissions($dto->permissions ?? []);

        return $role;
    }

    public function delete(Role $role): void
    {
        $role->delete();
    }
}
