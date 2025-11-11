<?php
namespace Src\Partners\Application\Actions;

use Src\Partners\Domain\Entities\Partner;
use Src\Partners\Domain\Repositories\PartnerRepositoryInterface;

/**
 * DeletePartnerAction application action.
 *
 * @package Src\Partners\Application\Actions
 */
class DeletePartnerAction
{
    public function __construct(private PartnerRepositoryInterface $repo) {}

    public function execute(Partner $partner): void
    {
        $this->repo->delete($partner);
    }
}


