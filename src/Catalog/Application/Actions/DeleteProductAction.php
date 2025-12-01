<?php

namespace Src\Catalog\Application\Actions;

use Illuminate\Support\Facades\Storage;
use Src\Catalog\Domain\Entities\Product;
use Src\Catalog\Domain\Entities\ProductAttributeValue;
use Src\Catalog\Domain\Repositories\AdminProductRepositoryInterface;

/**
 * DeleteProductAction application action.
 */
class DeleteProductAction
{
    public function __construct(private AdminProductRepositoryInterface $repo) {}

    public function execute(Product $product): void
    {
        // Delete product image if exists
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        // Delete associated attribute values
        ProductAttributeValue::where('product_id', $product->id)->delete();

        // Delete the product
        $this->repo->delete($product);
    }
}
