<?php

namespace Src\Content\Application\Actions;

use Src\Content\Application\DTOs\BlogPostDTO;
use Src\Content\Domain\Entities\BlogPost;
use Src\Content\Domain\Repositories\BlogPostRepositoryInterface;

/**
 * UpdateBlogPostAction application action.
 */
class UpdateBlogPostAction
{
    public function __construct(private BlogPostRepositoryInterface $repository) {}

    public function execute(BlogPost $post, BlogPostDTO $dto): BlogPost
    {
        return $this->repository->update($post, $dto);
    }
}
