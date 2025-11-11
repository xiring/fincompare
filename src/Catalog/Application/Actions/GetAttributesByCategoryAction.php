<?php
namespace Src\Catalog\Application\Actions;

use Illuminate\Support\Collection;
use Src\Catalog\Domain\Repositories\AttributeRepositoryInterface;

/**
 * GetAttributesByCategoryAction application action.
 *
 * @package Src\Catalog\Application\Actions
 */
class GetAttributesByCategoryAction
{
    public function __construct(private AttributeRepositoryInterface $repo) {}

    public function execute(int $productCategoryId): Collection
    {
        return $this->repo->byCategory($productCategoryId);
    }
}


