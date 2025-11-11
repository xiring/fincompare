<?php
namespace Src\Content\Application\Actions;

use Src\Content\Domain\Entities\CmsPage;

/**
 * DeleteCmsPageAction application action.
 *
 * @package Src\Content\Application\Actions
 */
class DeleteCmsPageAction
{
    public function execute(CmsPage $page): void
    {
        $page->delete();
    }
}


