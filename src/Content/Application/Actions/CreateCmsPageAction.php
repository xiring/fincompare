<?php
namespace Src\Content\Application\Actions;

use Src\Content\Domain\Entities\CmsPage;
use Src\Content\Application\DTOs\CmsPageDTO;

class CreateCmsPageAction
{
    public function execute(CmsPageDTO $dto): CmsPage
    {
        return CmsPage::create($dto->toArray());
    }
}


