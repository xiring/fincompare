<?php
namespace Src\Catalog\Application\Actions;

use Src\Catalog\Domain\Entities\ProductCategory;

/**
 * ShowProductCategoryAction application action.
 *
 * @package Src\Catalog\Application\Actions
 */
class ShowProductCategoryAction
{
    public function execute(ProductCategory $category): ProductCategory
    {
        return $category;
    }
}


