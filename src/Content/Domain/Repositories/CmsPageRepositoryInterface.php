<?php

namespace Src\Content\Domain\Repositories;

use Src\Content\Application\DTOs\CmsPageDTO;
use Src\Content\Domain\Entities\CmsPage;
use Src\Shared\Application\Criteria\ListCriteria;

/**
 * CmsPageRepositoryInterface interface.
 */
interface CmsPageRepositoryInterface
{
    /**
     * Handle Paginate.
     *
     * @return mixed
     */
    public function paginate(ListCriteria $criteria);

    public function find(int $id): ?CmsPage;

    public function create(CmsPageDTO $dto): CmsPage;

    public function update(CmsPage $cmsPage, CmsPageDTO $dto): CmsPage;

    public function delete(CmsPage $cmsPage): void;
}
