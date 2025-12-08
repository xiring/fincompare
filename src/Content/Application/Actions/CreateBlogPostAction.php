<?php

namespace Src\Content\Application\Actions;

use Src\Content\Application\DTOs\BlogPostDTO;
use Src\Content\Domain\Entities\BlogPost;
use Src\Content\Domain\Repositories\BlogPostRepositoryInterface;

/**
 * CreateBlogPostAction application action.
 */
class CreateBlogPostAction
{
    public function __construct(private BlogPostRepositoryInterface $repository) {}

    public function execute(BlogPostDTO $dto): BlogPost
    {
        return $this->repository->create($dto);
    }
}
