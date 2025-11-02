<?php
namespace Src\Content\Application\Actions;

use Src\Content\Domain\Entities\CmsPage;

class ShowCmsPageAction
{
    public function execute(CmsPage $page): CmsPage
    {
        return $page;
    }
}


