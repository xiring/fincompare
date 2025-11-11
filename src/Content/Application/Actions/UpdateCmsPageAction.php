<?php
namespace Src\Content\Application\Actions;

use Src\Content\Domain\Entities\CmsPage;
use Src\Content\Application\DTOs\CmsPageDTO;

/**
 * UpdateCmsPageAction application action.
 *
 * @package Src\Content\Application\Actions
 */
class UpdateCmsPageAction
{
    public function execute(CmsPage $page, CmsPageDTO $dto): CmsPage
    {
        $page->update($dto->toArray());
        return $page;
    }
}


