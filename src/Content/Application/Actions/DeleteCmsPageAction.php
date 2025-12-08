<?php

namespace Src\Content\Application\Actions;

use Src\Content\Domain\Entities\CmsPage;
use Src\Content\Domain\Repositories\CmsPageRepositoryInterface;

/**
 * DeleteCmsPageAction application action.
 */
class DeleteCmsPageAction
{
    public function __construct(private CmsPageRepositoryInterface $repository) {}

    public function execute(CmsPage $page): void
    {
        $this->repository->delete($page);
    }
}
