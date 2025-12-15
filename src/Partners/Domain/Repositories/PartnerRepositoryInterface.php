<?php

namespace Src\Partners\Domain\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Src\Partners\Application\DTOs\PartnerDTO;
use Src\Partners\Domain\Entities\Partner;
use Src\Shared\Application\Criteria\ListCriteria;

/**
 * PartnerRepositoryInterface interface.
 */
interface PartnerRepositoryInterface
{
    public function paginate(ListCriteria $criteria): LengthAwarePaginator;

    public function find(int $id): ?Partner;

    public function create(PartnerDTO $dto): Partner;

    public function update(Partner $partner, PartnerDTO $dto): Partner;

    public function delete(Partner $partner): void;
}
