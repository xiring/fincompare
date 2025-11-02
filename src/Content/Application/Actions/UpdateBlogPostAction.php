<?php
namespace Src\Content\Application\Actions;

use Src\Content\Domain\Entities\BlogPost;
use Src\Content\Application\DTOs\BlogPostDTO;

class UpdateBlogPostAction
{
    public function execute(BlogPost $post, BlogPostDTO $dto): BlogPost
    {
        $data = $dto->toArray();
        $data['tags'] = $dto->tags;
        $post->update($data);
        return $post;
    }
}


