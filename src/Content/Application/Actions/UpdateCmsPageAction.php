<?php
namespace Src\Content\Application\Actions;

use Src\Content\Domain\Entities\CmsPage;
use Src\Content\Application\DTOs\CmsPageDTO;

class UpdateCmsPageAction
{
    public function execute(CmsPage $page, CmsPageDTO $dto): CmsPage
    {
        $page->update($dto->toArray());
        return $page;
    }
}


