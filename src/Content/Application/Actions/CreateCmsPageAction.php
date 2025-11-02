<?php
namespace Src\Content\Application\Actions;

use Illuminate\Support\Str;
use Src\Content\Domain\Entities\CmsPage;

class CreateCmsPageAction
{
    public function execute(array $data): CmsPage
    {
        if (empty($data['slug']) && !empty($data['title'])) {
            $data['slug'] = Str::slug($data['title']);
        }
        return CmsPage::create($data);
    }
}


