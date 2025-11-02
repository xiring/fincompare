<?php
namespace Src\Catalog\Application\Actions;

use Src\Catalog\Domain\Entities\ProductCategory;

class ShowProductCategoryAction
{
    public function execute(ProductCategory $category): ProductCategory
    {
        return $category;
    }
}


