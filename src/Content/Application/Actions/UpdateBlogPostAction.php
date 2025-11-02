<?php
namespace Src\Content\Application\Actions;

use Illuminate\Support\Str;
use Src\Content\Domain\Entities\BlogPost;

class UpdateBlogPostAction
{
    public function execute(BlogPost $post, array $data): BlogPost
    {
        if (empty($data['slug']) && isset($data['title'])) {
            $data['slug'] = Str::slug($data['title']);
        }
        if (isset($data['tags']) && is_string($data['tags'])) {
            $decoded = json_decode($data['tags'], true);
            $data['tags'] = is_array($decoded) ? $decoded : array_filter(array_map('trim', explode(',', $data['tags'])));
        }
        $post->update($data);
        return $post;
    }
}


