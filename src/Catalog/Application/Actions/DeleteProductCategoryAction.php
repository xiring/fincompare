<?php
namespace Src\Catalog\Application\Actions;

use Src\Catalog\Domain\Entities\ProductCategory;
use Src\Catalog\Domain\Repositories\ProductCategoryRepositoryInterface;

/**
 * DeleteProductCategoryAction application action.
 *
 * @package Src\Catalog\Application\Actions
 */
class DeleteProductCategoryAction
{
    public function __construct(private ProductCategoryRepositoryInterface $repo) {}

    public function execute(ProductCategory $category): void
    {
        $this->repo->delete($category);
    }
}


