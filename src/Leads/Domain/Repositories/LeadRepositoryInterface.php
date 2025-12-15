<?php

namespace Src\Leads\Domain\Repositories;

use Closure;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Src\Leads\Application\DTOs\LeadDTO;
use Src\Leads\Domain\Entities\Lead;
use Src\Shared\Application\Criteria\ListCriteria;

/**
 * LeadRepositoryInterface interface.
 */
interface LeadRepositoryInterface
{
    public function paginate(ListCriteria $criteria): LengthAwarePaginator;

    public function find(int $id): ?Lead;

    public function update(Lead $lead, LeadDTO $dto): Lead;

    public function streamExport(array $filters = [], int $chunkSize = 500, ?Closure $onRow = null): void;
}
