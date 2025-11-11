<?php

namespace Src\Content\Application\Actions;

use Src\Content\Domain\Entities\CmsPage;

/**
 * ShowCmsPageAction application action.
 */
class ShowCmsPageAction
{
    public function execute(CmsPage $page): CmsPage
    {
        return $page;
    }
}
