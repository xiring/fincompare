<?php

namespace Src\Leads\Application\Actions;

use Src\Leads\Application\DTOs\LeadDTO;
use Src\Leads\Domain\Entities\Lead;
use Src\Leads\Domain\Repositories\LeadRepositoryInterface;

/**
 * UpdateLeadAction application action.
 */
class UpdateLeadAction
{
    public function __construct(private LeadRepositoryInterface $repo) {}

    public function execute(Lead $lead, LeadDTO $dto): Lead
    {
        return $this->repo->update($lead, $dto);
    }
}
