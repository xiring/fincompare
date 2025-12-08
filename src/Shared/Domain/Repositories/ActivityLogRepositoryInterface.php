<?php

namespace Src\Shared\Domain\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Spatie\Activitylog\Models\Activity;

/**
 * ActivityLogRepositoryInterface interface.
 */
interface ActivityLogRepositoryInterface
{
    public function paginate(array $filters = [], int $perPage = 20): LengthAwarePaginator;

    public function find(int $id): ?Activity;
}

