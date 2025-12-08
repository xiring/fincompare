<?php

namespace Src\Catalog\Application\Actions;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Src\Catalog\Application\DTOs\ProductDTO;
use Src\Catalog\Application\Services\ProductAttributeSyncService;
use Src\Catalog\Domain\Entities\Product;
use Src\Catalog\Domain\Repositories\AdminProductRepositoryInterface;

/**
 * CreateProductAction application action.
 */
class CreateProductAction
{
    public function __construct(private AdminProductRepositoryInterface $repository) {}

    public function execute(array $data, array $attributesInput): Product
    {
        if (empty($data['slug'])) {
            $baseSlug = Str::slug($data['name']);
            $slug = $baseSlug;
            $counter = 1;

            // Ensure slug is unique using repository
            while ($this->repository->slugExists($slug)) {
                $slug = $baseSlug.'-'.$counter;
                $counter++;
            }
            $data['slug'] = $slug;
        }
        $data['is_featured'] = (bool) ($data['is_featured'] ?? false);

        return DB::transactionWithRetry(function () use ($data, $attributesInput) {
            $dto = ProductDTO::fromArray($data);
            $product = $this->repository->create($dto);
            app(ProductAttributeSyncService::class)->sync($product, $attributesInput);

            return $product;
        });
    }
}
