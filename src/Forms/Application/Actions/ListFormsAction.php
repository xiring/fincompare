<?php

namespace Src\Forms\Application\Actions;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Src\Forms\Domain\Repositories\FormRepositoryInterface;

/**
 * ListFormsAction application action.
 */
class ListFormsAction
{
    public function __construct(private FormRepositoryInterface $repo) {}

    public function execute(array $filters = [], int $perPage = 20): LengthAwarePaginator
    {
        return $this->repo->paginate($filters, $perPage);
    }
}

