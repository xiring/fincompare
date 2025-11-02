<?php
namespace Src\Catalog\Application\Actions;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Src\Catalog\Domain\Entities\Product;
use Src\Catalog\Application\Services\ProductAttributeSyncService;

class CreateProductAction
{
    public function execute(array $data, array $attributesInput): Product
    {
        if (empty($data['slug'])) $data['slug'] = Str::slug($data['name']);
        $data['is_featured'] = (bool)($data['is_featured'] ?? false);

        return DB::transactionWithRetry(function () use ($data, $attributesInput) {
            $product = Product::create($data);
            app(ProductAttributeSyncService::class)->sync($product, $attributesInput);
            return $product;
        });
    }
}
