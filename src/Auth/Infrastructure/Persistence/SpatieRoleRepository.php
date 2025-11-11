<?php

namespace Src\Auth\Infrastructure\Persistence;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Spatie\Permission\Models\Role;
use Src\Auth\Application\DTOs\RoleDTO;
use Src\Auth\Domain\Repositories\RoleRepositoryInterface;

/**
 * SpatieRoleRepository repository.
 */
class SpatieRoleRepository implements RoleRepositoryInterface
{
    public function paginate(array $filters = [], int $perPage = 20): LengthAwarePaginator
    {
        $sort = in_array(($filters['sort'] ?? ''), ['id', 'name', 'created_at']) ? $filters['sort'] : 'id';
        $dir = strtolower($filters['dir'] ?? 'desc') === 'asc' ? 'asc' : 'desc';

        return Role::query()
            ->when(($filters['q'] ?? null), fn ($q, $qStr) => $q->where('name', 'like', '%'.$qStr.'%'))
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
