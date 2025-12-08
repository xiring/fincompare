<?php

namespace Src\Content\Application\Actions;

use Src\Content\Domain\Entities\BlogPost;
use Src\Content\Domain\Repositories\BlogPostRepositoryInterface;

/**
 * DeleteBlogPostAction application action.
 */
class DeleteBlogPostAction
{
    public function __construct(private BlogPostRepositoryInterface $repository) {}

    public function execute(BlogPost $post): void
    {
        $this->repository->delete($post);
    }
}
