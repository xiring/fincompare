<?php

namespace Src\Content\Application\Actions;

use Src\Content\Domain\Repositories\CmsPageRepositoryInterface;
use Src\Shared\Application\Criteria\ListCriteria;

/**
 * ListCmsPagesAction application action.
 */
class ListCmsPagesAction
{
    public function __construct(private CmsPageRepositoryInterface $repo) {}

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
