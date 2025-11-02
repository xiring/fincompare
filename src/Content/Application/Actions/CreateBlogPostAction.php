<?php
namespace Src\Content\Application\Actions;

use Illuminate\Support\Str;
use Src\Content\Domain\Entities\BlogPost;

class CreateBlogPostAction
{
    public function execute(array $data): BlogPost
    {
        if (empty($data['slug']) && !empty($data['title'])) {
            $data['slug'] = Str::slug($data['title']);
        }
        if (isset($data['tags']) && is_string($data['tags'])) {
            $decoded = json_decode($data['tags'], true);
            $data['tags'] = is_array($decoded) ? $decoded : array_filter(array_map('trim', explode(',', $data['tags'])));
        }
        return BlogPost::create($data);
    }
}


