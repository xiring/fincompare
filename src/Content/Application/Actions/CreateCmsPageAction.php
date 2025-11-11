<?php
namespace Src\Content\Application\Actions;

use Src\Content\Domain\Entities\CmsPage;
use Src\Content\Application\DTOs\CmsPageDTO;

/**
 * CreateCmsPageAction application action.
 *
 * @package Src\Content\Application\Actions
 */
class CreateCmsPageAction
{
    public function execute(CmsPageDTO $dto): CmsPage
    {
        return CmsPage::create($dto->toArray());
    }
}


