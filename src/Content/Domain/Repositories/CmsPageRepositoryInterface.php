<?php
namespace Src\Content\Domain\Repositories;

/**
 * CmsPageRepositoryInterface interface.
 *
 * @package Src\Content\Domain\Repositories
 */
interface CmsPageRepositoryInterface
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


