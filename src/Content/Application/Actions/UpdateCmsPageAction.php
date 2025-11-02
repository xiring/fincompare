<?php
namespace Src\Content\Application\Actions;

use Illuminate\Support\Str;
use Src\Content\Domain\Entities\CmsPage;

class UpdateCmsPageAction
{
    public function execute(CmsPage $page, array $data): CmsPage
    {
        if (empty($data['slug']) && isset($data['title'])) {
            $data['slug'] = Str::slug($data['title']);
        }
        $page->update($data);
        return $page;
    }
}


