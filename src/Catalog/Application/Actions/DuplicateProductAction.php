<?php

namespace Src\Catalog\Application\Actions;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Src\Catalog\Application\DTOs\ProductDTO;
use Src\Catalog\Application\Services\ProductAttributeSyncService;
use Src\Catalog\Domain\Entities\Product;
use Src\Catalog\Domain\Repositories\AdminProductRepositoryInterface;

/**
 * DuplicateProductAction application action.
 */
class DuplicateProductAction
{
    public function __construct(private AdminProductRepositoryInterface $repository) {}

    public function execute(Product $product): Product
    {
        // Load the product with its attribute values and attributes
        $product->load('attributeValues.attribute');

        // Generate unique slug using repository
        $baseSlug = Str::slug('Copy of '.$product->name);
        $slug = $baseSlug;
        $counter = 1;
        while ($this->repository->slugExists($slug)) {
            $slug = $baseSlug.'-'.$counter;
            $counter++;
        }

        return DB::transactionWithRetry(function () use ($product, $slug) {
            // Create new product (without image - user can upload new one)
            $dto = ProductDTO::fromArray([
                'partner_id' => $product->partner_id,
                'product_category_id' => $product->product_category_id,
                'name' => 'Copy of '.$product->name,
                'slug' => $slug,
                'description' => $product->description,
                'image' => null, // Don't copy image - user can upload new one
                'is_featured' => false, // Don't copy featured status
                'status' => 'inactive', // Set to inactive by default
            ]);
            $duplicatedProduct = $this->repository->create($dto);

            // Duplicate all attribute values
            $attributesInput = [];
            foreach ($product->attributeValues as $attributeValue) {
                $value = $attributeValue->getScalarValue();
                if ($value !== null) {
                    $attributesInput[$attributeValue->attribute_id] = $value;
                }
            }

            // Sync attributes using the service
            if (!empty($attributesInput)) {
                app(ProductAttributeSyncService::class)->sync($duplicatedProduct, $attributesInput);
            }

            return $duplicatedProduct->load(['partner', 'productCategory', 'attributeValues.attribute']);
        });
    }
}

