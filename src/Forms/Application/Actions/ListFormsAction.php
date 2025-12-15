<?php

namespace Src\Forms\Application\Actions;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Src\Forms\Domain\Repositories\FormRepositoryInterface;
use Src\Shared\Application\Criteria\ListCriteria;

/**
 * ListFormsAction application action.
 */
class ListFormsAction
{
    public function __construct(private FormRepositoryInterface $repo) {}

    public function execute(ListCriteria $criteria): LengthAwarePaginator
    {
        return $this->repo->paginate($criteria);
    }
}

