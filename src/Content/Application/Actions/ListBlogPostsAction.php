<?php

namespace Src\Content\Application\Actions;

use Src\Content\Domain\Repositories\BlogPostRepositoryInterface;
use Src\Shared\Application\Criteria\ListCriteria;

/**
 * ListBlogPostsAction application action.
 */
class ListBlogPostsAction
{
    public function __construct(private BlogPostRepositoryInterface $repo) {}

    /**
     * Handle Execute.
     *
     * @return mixed
     */
    public function execute(ListCriteria $criteria)
    {
        return $this->repo->paginate($criteria);
    }
}
