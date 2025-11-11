<?php

namespace Src\Leads\Application\Actions;

use Illuminate\Support\Facades\DB;
use Src\Leads\Application\DTOs\LeadDTO;
use Src\Leads\Domain\Entities\Lead;

/**
 * CaptureLeadAction application action.
 */
class CaptureLeadAction
{
    public function execute(LeadDTO $dto): Lead
    {
        return DB::transactionWithRetry(function () use ($dto) {
            return Lead::create($dto->toArray());
        });
    }
}
