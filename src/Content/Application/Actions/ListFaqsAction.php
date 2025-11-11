<?php

namespace Src\Content\Application\Actions;

use Src\Content\Domain\Repositories\FaqRepositoryInterface;

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
    public function execute(array $filters = [], int $perPage = 20)
    {
        return $this->repo->paginate($filters, $perPage);
    }
}
