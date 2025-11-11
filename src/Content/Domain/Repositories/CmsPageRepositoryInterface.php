<?php

namespace Src\Content\Domain\Repositories;

/**
 * CmsPageRepositoryInterface interface.
 */
interface CmsPageRepositoryInterface
{
    /**
     * Handle Paginate.
     *
     * @return mixed
     */
    public function paginate(array $filters = [], int $perPage = 20);
}
