<?php
namespace Src\Leads\Application\Actions;

use Closure;
use Src\Leads\Domain\Repositories\LeadRepositoryInterface;

/**
 * ExportLeadsCsvAction application action.
 *
 * @package Src\Leads\Application\Actions
 */
class ExportLeadsCsvAction
{
    public function __construct(private LeadRepositoryInterface $repo) {}

    public function execute(array $filters = [], int $chunkSize = 500, ?Closure $onRow = null): void
    {
        $this->repo->streamExport($filters, $chunkSize, $onRow);
    }
}


