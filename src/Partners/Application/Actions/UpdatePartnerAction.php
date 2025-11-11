<?php

namespace Src\Partners\Application\Actions;

use Src\Partners\Application\DTOs\PartnerDTO;
use Src\Partners\Domain\Entities\Partner;
use Src\Partners\Domain\Repositories\PartnerRepositoryInterface;

/**
 * UpdatePartnerAction application action.
 */
class UpdatePartnerAction
{
    public function __construct(private PartnerRepositoryInterface $repo) {}

    public function execute(Partner $partner, PartnerDTO $dto): Partner
    {
        return $this->repo->update($partner, $dto);
    }
}
