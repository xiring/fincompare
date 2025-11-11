<?php

namespace Src\Auth\Application\Actions;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Src\Auth\Domain\Repositories\RoleRepositoryInterface;

/**
 * ListRolesAction application action.
 */
class ListRolesAction
{
    public function __construct(private RoleRepositoryInterface $repo) {}

    public function execute(array $filters = [], int $perPage = 20): LengthAwarePaginator
    {
        return $this->repo->paginate($filters, $perPage);
    }
}
