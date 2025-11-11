<?php

namespace Src\Partners\Application\Actions;

use Src\Partners\Domain\Entities\Partner;

/**
 * ShowPartnerAction application action.
 */
class ShowPartnerAction
{
    public function execute(Partner $partner): Partner
    {
        return $partner;
    }
}
