<?php

namespace Src\Content\Application\Actions;

use Src\Content\Application\DTOs\CmsPageDTO;
use Src\Content\Domain\Entities\CmsPage;

/**
 * CreateCmsPageAction application action.
 */
class CreateCmsPageAction
{
    public function execute(CmsPageDTO $dto): CmsPage
    {
        return CmsPage::create($dto->toArray());
    }
}
