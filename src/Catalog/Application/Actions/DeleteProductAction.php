<?php
namespace Src\Catalog\Application\Actions;

use Src\Catalog\Domain\Entities\Product;
use Src\Catalog\Domain\Entities\ProductAttributeValue;
use Src\Catalog\Domain\Repositories\AdminProductRepositoryInterface;

class DeleteProductAction
{
    public function __construct(private AdminProductRepositoryInterface $repo) {}

    public function execute(Product $product): void
    {
        ProductAttributeValue::where('product_id', $product->id)->delete();
        $this->repo->delete($product);
    }
}


