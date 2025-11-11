<?php
namespace Src\Partners\Application\Actions;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Src\Partners\Domain\Repositories\PartnerRepositoryInterface;

/**
 * ListPartnersAction application action.
 *
 * @package Src\Partners\Application\Actions
 */
class ListPartnersAction
{
    public function __construct(private PartnerRepositoryInterface $repo) {}

    public function execute(array $filters = [], int $perPage = 20): LengthAwarePaginator
    {
        return $this->repo->paginate($filters, $perPage);
    }
}


