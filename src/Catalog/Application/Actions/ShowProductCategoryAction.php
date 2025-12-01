<?php

namespace Src\Catalog\Application\Actions;

use Src\Catalog\Domain\Entities\ProductCategory;

/**
 * ShowProductCategoryAction application action.
 */
class ShowProductCategoryAction
{
    public function execute(ProductCategory $category): ProductCategory
    {
        return $category->load(['preForm', 'postForm']);
    }
}
