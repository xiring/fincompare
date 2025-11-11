<?php
namespace Src\Catalog\Application\Actions;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Src\Catalog\Domain\Repositories\AttributeRepositoryInterface;

/**
 * ListAttributesAction application action.
 *
 * @package Src\Catalog\Application\Actions
 */
class ListAttributesAction
{
    public function __construct(private AttributeRepositoryInterface $repo) {}

    public function execute(array $filters = [], int $perPage = 20): LengthAwarePaginator
    {
        return $this->repo->paginate($filters, $perPage);
    }
}


