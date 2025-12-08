<?php

namespace Src\Content\Application\Actions;

use Src\Content\Application\DTOs\CmsPageDTO;
use Src\Content\Domain\Entities\CmsPage;
use Src\Content\Domain\Repositories\CmsPageRepositoryInterface;

/**
 * UpdateCmsPageAction application action.
 */
class UpdateCmsPageAction
{
    public function __construct(private CmsPageRepositoryInterface $repository) {}

    public function execute(CmsPage $page, CmsPageDTO $dto): CmsPage
    {
        return $this->repository->update($page, $dto);
    }
}
