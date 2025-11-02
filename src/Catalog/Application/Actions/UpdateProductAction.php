<?php
namespace Src\Catalog\Application\Actions;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Src\Catalog\Domain\Entities\Attribute;
use Src\Catalog\Domain\Entities\Product;
use Src\Catalog\Domain\Entities\ProductAttributeValue;
use Src\Catalog\Application\Services\ProductAttributeSyncService;

class UpdateProductAction
{
    public function execute(Product $product, array $data, array $attributesInput): Product
    {
        if (empty($data['slug'])) $data['slug'] = Str::slug($data['name']);
        $data['is_featured'] = (bool)($data['is_featured'] ?? false);

        return DB::transactionWithRetry(function () use ($product, $data, $attributesInput) {
            $product->update($data);
            app(ProductAttributeSyncService::class)->sync($product, $attributesInput);
            return $product;
        });
    }
}
