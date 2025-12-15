<?php

namespace Src\Content\Application\Actions;

use Src\Content\Domain\Repositories\FaqRepositoryInterface;
use Src\Shared\Application\Criteria\ListCriteria;

/**
 * ListFaqsAction application action.
 */
class ListFaqsAction
{
    public function __construct(private readonly FaqRepositoryInterface $repo) {}

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
