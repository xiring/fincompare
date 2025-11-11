<?php
namespace Src\Content\Application\Actions;

use Src\Content\Domain\Repositories\BlogPostRepositoryInterface;

/**
 * ListBlogPostsAction application action.
 *
 * @package Src\Content\Application\Actions
 */
class ListBlogPostsAction
{
    public function __construct(private BlogPostRepositoryInterface $repo) {}

    /**
     * Handle Execute.
     *
     * @param array $filters
     * @param int $perPage
     * @return mixed
     */
    public function execute(array $filters = [], int $perPage = 20)
    {
        return $this->repo->paginate($filters, $perPage);
    }
}


