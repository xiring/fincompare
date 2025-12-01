<?php

namespace Src\Partners\Application\Actions;

use Illuminate\Support\Facades\Storage;
use Src\Partners\Domain\Entities\Partner;
use Src\Partners\Domain\Repositories\PartnerRepositoryInterface;

/**
 * DeletePartnerAction application action.
 */
class DeletePartnerAction
{
    public function __construct(private PartnerRepositoryInterface $repo) {}

    public function execute(Partner $partner): void
    {
        // Delete partner logo if exists
        if ($partner->logo_path) {
            Storage::disk('public')->delete($partner->logo_path);
        }

        $this->repo->delete($partner);
    }
}
