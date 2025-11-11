<?php
namespace Src\Content\Domain\Repositories;

/**
 * BlogPostRepositoryInterface interface.
 *
 * @package Src\Content\Domain\Repositories
 */
interface BlogPostRepositoryInterface
{
    /**
     * Handle Paginate.
     *
     * @param array $filters
     * @param int $perPage
     * @return mixed
     */
    public function paginate(array $filters = [], int $perPage = 20);
}


