<?php
namespace Src\Content\Application\Actions;

use Src\Content\Domain\Repositories\BlogPostRepositoryInterface;

class ListBlogPostsAction
{
    public function __construct(private BlogPostRepositoryInterface $repo) {}

    public function execute(array $filters = [], int $perPage = 20)
    {
        return $this->repo->paginate($filters, $perPage);
    }
}


