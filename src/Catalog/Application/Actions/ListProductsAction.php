<?php
namespace Src\Catalog\Application\Actions;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Src\Catalog\Domain\Repositories\AdminProductRepositoryInterface;

/**
 * ListProductsAction application action.
 *
 * @package Src\Catalog\Application\Actions
 */
class ListProductsAction
{
    public function __construct(private AdminProductRepositoryInterface $repo) {}

    public function execute(array $filters = [], int $perPage = 20): LengthAwarePaginator
    {
        return $this->repo->paginate($filters, $perPage);
    }
}


