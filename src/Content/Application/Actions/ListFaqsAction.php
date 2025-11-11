<?php
namespace Src\Content\Application\Actions;

use Src\Content\Domain\Repositories\FaqRepositoryInterface;

class ListFaqsAction
{
    public function __construct(private readonly FaqRepositoryInterface $repo) {}

    public function execute(array $filters = [], int $perPage = 20)
    {
        return $this->repo->paginate($filters, $perPage);
    }
}


