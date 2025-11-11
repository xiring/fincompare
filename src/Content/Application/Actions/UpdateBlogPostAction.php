<?php
namespace Src\Content\Application\Actions;

use Src\Content\Domain\Entities\BlogPost;
use Src\Content\Application\DTOs\BlogPostDTO;

/**
 * UpdateBlogPostAction application action.
 *
 * @package Src\Content\Application\Actions
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


