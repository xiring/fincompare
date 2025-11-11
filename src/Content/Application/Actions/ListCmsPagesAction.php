<?php
namespace Src\Content\Application\Actions;

use Src\Content\Domain\Repositories\CmsPageRepositoryInterface;

/**
 * ListCmsPagesAction application action.
 *
 * @package Src\Content\Application\Actions
 */
class ListCmsPagesAction
{
    public function __construct(private CmsPageRepositoryInterface $repo) {}

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


