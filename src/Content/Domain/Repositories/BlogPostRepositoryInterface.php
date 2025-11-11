<?php

namespace Src\Content\Domain\Repositories;

/**
 * BlogPostRepositoryInterface interface.
 */
interface BlogPostRepositoryInterface
{
    /**
     * Handle Paginate.
     *
     * @return mixed
     */
    public function paginate(array $filters = [], int $perPage = 20);
}
