<?php
namespace Src\Partners\Application\Actions;

use Src\Partners\Domain\Entities\Partner;

class ShowPartnerAction
{
    public function execute(Partner $partner): Partner
    {
        return $partner;
    }
}


