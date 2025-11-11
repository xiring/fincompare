<?php

namespace Src\Auth\Application\Actions;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Src\Auth\Domain\Repositories\PermissionRepositoryInterface;

/**
 * ListPermissionsAction application action.
 */
class ListPermissionsAction
{
    public function __construct(private PermissionRepositoryInterface $repo) {}

    public function execute(array $filters = [], int $perPage = 20): LengthAwarePaginator
    {
        return $this->repo->paginate($filters, $perPage);
    }
}
