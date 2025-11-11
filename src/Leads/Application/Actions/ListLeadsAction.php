<?php

namespace Src\Leads\Application\Actions;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Src\Leads\Domain\Repositories\LeadRepositoryInterface;

/**
 * ListLeadsAction application action.
 */
class ListLeadsAction
{
    public function __construct(private LeadRepositoryInterface $repo) {}

    public function execute(array $filters = [], int $perPage = 20): LengthAwarePaginator
    {
        return $this->repo->paginate($filters, $perPage);
    }
}
