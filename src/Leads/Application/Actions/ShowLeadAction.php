<?php
namespace Src\Leads\Application\Actions;

use Src\Leads\Domain\Entities\Lead;

class ShowLeadAction
{
    public function execute(Lead $lead): Lead
    {
        return $lead->load(['product','productCategory']);
    }
}


