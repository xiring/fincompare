<?php

namespace Src\Content\Application\Actions;

use Src\Content\Domain\Repositories\CmsPageRepositoryInterface;

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
    public function execute(array $filters = [], int $perPage = 20)
    {
        return $this->repo->paginate($filters, $perPage);
    }
}
