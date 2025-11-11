<?php
namespace Src\Content\Application\Actions;

use Src\Content\Domain\Entities\BlogPost;
use Src\Content\Application\DTOs\BlogPostDTO;

/**
 * CreateBlogPostAction application action.
 *
 * @package Src\Content\Application\Actions
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


