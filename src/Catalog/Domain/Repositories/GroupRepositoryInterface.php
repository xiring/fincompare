<?php

namespace Src\Catalog\Domain\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Src\Catalog\Application\DTOs\GroupDTO;
use Src\Catalog\Domain\Entities\Group;

/**
 * GroupRepositoryInterface interface.
 */
interface GroupRepositoryInterface
{
    public function paginate(array $filters = [], int $perPage = 20): LengthAwarePaginator;

    public function find(int $id): ?Group;

    public function create(GroupDTO $dto): Group;

    public function update(Group $group, GroupDTO $dto): Group;

    public function delete(Group $group): void;
}


