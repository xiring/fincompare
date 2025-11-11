<?php
namespace Src\Auth\Infrastructure\Persistence;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Spatie\Permission\Models\Permission;
use Src\Auth\Application\DTOs\PermissionDTO;
use Src\Auth\Domain\Repositories\PermissionRepositoryInterface;

/**
 * SpatiePermissionRepository repository.
 *
 * @package Src\Auth\Infrastructure\Persistence
 */
class SpatiePermissionRepository implements PermissionRepositoryInterface
{
    public function paginate(array $filters = [], int $perPage = 20): LengthAwarePaginator
    {
        $sort = in_array(($filters['sort'] ?? ''), ['id','name','created_at']) ? $filters['sort'] : 'id';
        $dir = strtolower($filters['dir'] ?? 'desc') === 'asc' ? 'asc' : 'desc';
        return Permission::query()
            ->when(($filters['q'] ?? null), fn($q,$qStr)=>$q->where('name','like','%'.$qStr.'%'))
            ->orderBy($sort,$dir)
            ->paginate($perPage)->withQueryString();
    }

    public function find(int $id): ?Permission
    {
        return Permission::find($id);
    }

    public function create(PermissionDTO $dto): Permission
    {
        return Permission::create(['name'=>$dto->name]);
    }

    public function update(Permission $permission, PermissionDTO $dto): Permission
    {
        $permission->update(['name'=>$dto->name]);
        return $permission;
    }

    public function delete(Permission $permission): void
    {
        $permission->delete();
    }
}


