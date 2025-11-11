<?php

namespace Src\Auth\Domain\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Spatie\Permission\Models\Role;
use Src\Auth\Application\DTOs\RoleDTO;

/**
 * RoleRepositoryInterface interface.
 */
interface RoleRepositoryInterface
{
    public function paginate(array $filters = [], int $perPage = 20): LengthAwarePaginator;

    public function find(int $id): ?Role;

    public function create(RoleDTO $dto): Role;

    public function update(Role $role, RoleDTO $dto): Role;

    public function delete(Role $role): void;
}
