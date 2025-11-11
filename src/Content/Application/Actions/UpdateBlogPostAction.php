<?php

namespace Src\Content\Application\Actions;

use Src\Content\Application\DTOs\BlogPostDTO;
use Src\Content\Domain\Entities\BlogPost;

/**
 * UpdateBlogPostAction application action.
 */
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
