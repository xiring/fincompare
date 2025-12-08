<?php

namespace Src\Content\Application\Actions;

use Src\Content\Application\DTOs\CmsPageDTO;
use Src\Content\Domain\Entities\CmsPage;
use Src\Content\Domain\Repositories\CmsPageRepositoryInterface;

/**
 * CreateCmsPageAction application action.
 */
class CreateCmsPageAction
{
    public function __construct(private CmsPageRepositoryInterface $repository) {}

    public function execute(CmsPageDTO $dto): CmsPage
    {
        return $this->repository->create($dto);
    }
}
