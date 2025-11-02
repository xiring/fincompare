<?php
namespace Src\Leads\Application\Actions;

use Illuminate\Support\Facades\DB;
use Src\Leads\Domain\Entities\Lead;
use Src\Leads\Application\DTOs\LeadDTO;

class CaptureLeadAction
{
    public function execute(LeadDTO $dto): Lead
    {
        return DB::transactionWithRetry(function () use ($dto) {
            return Lead::create($dto->toArray());
        });
    }
}


