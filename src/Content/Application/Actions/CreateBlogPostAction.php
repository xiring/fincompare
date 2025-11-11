<?php

namespace Src\Content\Application\Actions;

use Src\Content\Application\DTOs\BlogPostDTO;
use Src\Content\Domain\Entities\BlogPost;

/**
 * CreateBlogPostAction application action.
 */
class CreateBlogPostAction
{
    public function execute(BlogPostDTO $dto): BlogPost
    {
        $data = $dto->toArray();
        $data['tags'] = $dto->tags;

        return BlogPost::create($data);
    }
}
