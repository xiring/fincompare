<?php

namespace Src\Auth\Domain\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Spatie\Permission\Models\Permission;
use Src\Auth\Application\DTOs\PermissionDTO;
use Src\Shared\Application\Criteria\ListCriteria;

/**
 * PermissionRepositoryInterface interface.
 */
interface PermissionRepositoryInterface
{
    public function paginate(ListCriteria $criteria): LengthAwarePaginator;

    public function find(int $id): ?Permission;

    public function create(PermissionDTO $dto): Permission;

    public function update(Permission $permission, PermissionDTO $dto): Permission;

    public function delete(Permission $permission): void;
}
