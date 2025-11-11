<?php
namespace Src\Content\Application\Actions;

use Src\Content\Domain\Entities\CmsPage;

/**
 * ShowCmsPageAction application action.
 *
 * @package Src\Content\Application\Actions
 */
class ShowCmsPageAction
{
    public function execute(CmsPage $page): CmsPage
    {
        return $page;
    }
}


