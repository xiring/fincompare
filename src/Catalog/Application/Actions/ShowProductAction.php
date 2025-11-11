<?php
namespace Src\Catalog\Application\Actions;

use Src\Catalog\Domain\Entities\Product;

/**
 * ShowProductAction application action.
 *
 * @package Src\Catalog\Application\Actions
 */
class ShowProductAction
{
    public function execute(Product $product): Product
    {
        return $product->load(['partner','productCategory','attributeValues.attribute']);
    }
}


