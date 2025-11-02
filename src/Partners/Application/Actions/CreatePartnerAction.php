<?php
namespace Src\Partners\Application\Actions;

use Src\Partners\Application\DTOs\PartnerDTO;
use Src\Partners\Domain\Entities\Partner;
use Src\Partners\Domain\Repositories\PartnerRepositoryInterface;

class CreatePartnerAction
{
    public function __construct(private PartnerRepositoryInterface $repo) {}

    public function execute(PartnerDTO $dto): Partner
    {
        return $this->repo->create($dto);
    }
}


