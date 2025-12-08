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
        $sort = in_array(($filters['sort'] ?? ''), ['id', 'created_at', 'log_name']) ? $filters['sort'] : 'id';
        $dir = strtolower($filters['dir'] ?? 'desc') === 'asc' ? 'asc' : 'desc';

        $query = Activity::query()
            ->when($filters['q'] ?? null, function ($q, $qStr) {
                $q->where('description', 'like', '%'.$qStr.'%')
                    ->orWhereHas('causer', fn ($q) => $q->where('name', 'like', '%'.$qStr.'%'));
            })
            ->when($filters['log_name'] ?? null, fn ($q, $n) => $q->where('log_name', $n))
            ->when($filters['causer_id'] ?? null, fn ($q, $id) => $q->where('causer_id', $id))
            ->when($filters['subject_type'] ?? null, fn ($q, $t) => $q->where('subject_type', $t))
            ->orderBy($sort, $dir);

        return $query->paginate($perPage)->withQueryString();
    }

    public function find(int $id): ?Activity
    {
        return Activity::find($id);
    }
}

