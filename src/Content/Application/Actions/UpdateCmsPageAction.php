<?php

namespace Src\Content\Application\Actions;

use Src\Content\Application\DTOs\CmsPageDTO;
use Src\Content\Domain\Entities\CmsPage;

/**
 * UpdateCmsPageAction application action.
 */
class UpdateCmsPageAction
{
    public function execute(CmsPage $page, CmsPageDTO $dto): CmsPage
    {
        $page->update($dto->toArray());

        return $page;
    }
}
