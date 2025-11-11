<?php
namespace Src\Leads\Domain\Repositories;

use Closure;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Src\Leads\Application\DTOs\LeadDTO;
use Src\Leads\Domain\Entities\Lead;

/**
 * LeadRepositoryInterface interface.
 *
 * @package Src\Leads\Domain\Repositories
 */
interface LeadRepositoryInterface
{
    public function paginate(array $filters = [], int $perPage = 20): LengthAwarePaginator;
    public function find(int $id): ?Lead;
    public function update(Lead $lead, LeadDTO $dto): Lead;
    public function streamExport(array $filters = [], int $chunkSize = 500, ?Closure $onRow = null): void;
}


