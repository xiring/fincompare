<?php

namespace Src\Leads\Infrastructure\Persistence;

use Closure;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Src\Leads\Application\DTOs\LeadDTO;
use Src\Leads\Domain\Entities\Lead;
use Src\Leads\Domain\Repositories\LeadRepositoryInterface;
use Src\Shared\Application\Criteria\ListCriteria;

/**
 * EloquentLeadRepository repository.
 */
class EloquentLeadRepository implements LeadRepositoryInterface
{
    public function paginate(ListCriteria $criteria): LengthAwarePaginator
    {
        $sort = in_array(($criteria->getSort() ?? ''), ['id', 'full_name', 'email', 'status', 'created_at']) ? $criteria->getSort() : 'id';
        $dir = $criteria->getDir();
        $filters = $criteria->filters();
        $perPage = $criteria->getPerPage() ?? 20;

        return Lead::with(['product', 'productCategory'])
            ->when(($filters['status'] ?? null), fn ($q, $status) => $q->where('status', $status))
            ->when($criteria->getSearch(), function ($q, $qStr) {
                $q->where(function ($qq) use ($qStr) {
                    $qq->where('full_name', 'like', '%'.$qStr.'%')
                        ->orWhere('email', 'like', '%'.$qStr.'%')
                        ->orWhere('mobile_number', 'like', '%'.$qStr.'%');
                });
            })
            ->orderBy($sort, $dir)
            ->paginate($perPage)->withQueryString();
    }

    public function find(int $id): ?Lead
    {
        return Lead::with(['product', 'productCategory'])->find($id);
    }

    public function update(Lead $lead, LeadDTO $dto): Lead
    {
        $lead->update(array_filter($dto->toArray(), fn ($v) => $v !== null));

        return $lead;
    }

    public function streamExport(array $filters = [], int $chunkSize = 500, ?Closure $onRow = null): void
    {
        Lead::with(['product', 'productCategory'])
            ->when(($filters['status'] ?? null), fn ($q, $status) => $q->where('status', $status))
            ->orderByDesc('id')
            ->chunk($chunkSize, function ($chunk) use ($onRow) {
                foreach ($chunk as $lead) {
                    if ($onRow) {
                        $onRow($lead);
                    }
                }
            });
    }
}
