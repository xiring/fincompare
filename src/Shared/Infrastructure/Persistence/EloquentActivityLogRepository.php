<?php

namespace Src\Shared\Infrastructure\Persistence;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Spatie\Activitylog\Models\Activity;
use Src\Shared\Domain\Repositories\ActivityLogRepositoryInterface;

/**
 * EloquentActivityLogRepository repository.
 */
class EloquentActivityLogRepository implements ActivityLogRepositoryInterface
{
    public function paginate(array $filters = [], int $perPage = 20): LengthAwarePaginator
    {
        $query = Activity::query()
            ->when($filters['log_name'] ?? null, fn ($q, $n) => $q->where('log_name', $n))
            ->when($filters['causer_id'] ?? null, fn ($q, $id) => $q->where('causer_id', $id))
            ->when($filters['subject_type'] ?? null, fn ($q, $t) => $q->where('subject_type', $t))
            ->orderByDesc('id');

        return $query->paginate($perPage);
    }

    public function find(int $id): ?Activity
    {
        return Activity::find($id);
    }
}

