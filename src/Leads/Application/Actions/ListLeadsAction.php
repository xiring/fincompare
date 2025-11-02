<?php
namespace Src\Leads\Application\Actions;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Src\Leads\Domain\Repositories\LeadRepositoryInterface;

class ListLeadsAction
{
    public function __construct(private LeadRepositoryInterface $repo) {}

    public function execute(array $filters = [], int $perPage = 20): LengthAwarePaginator
    {
        return $this->repo->paginate($filters, $perPage);
    }
}


