<?php

namespace Src\Catalog\Application\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Src\Catalog\Application\DTOs\ProductListFiltersDTO;
use Src\Catalog\Domain\Entities\Product;
use Src\Catalog\Domain\Entities\ProductCategory;
use Src\Partners\Domain\Entities\Partner;

/**
 * PublicProductService - Service for public-facing product operations.
 */
class PublicProductService
{
    private const DEFAULT_PER_PAGE = 12;

    public function __construct() {}

    /**
     * Get paginated products with filters.
     */
    public function getPaginatedProducts(ProductListFiltersDTO $filters): LengthAwarePaginator
    {
        $query = Product::query()
            ->with(['partner'])
            ->where('status', 'active');

        // Apply search query
        if ($filters->searchQuery) {
            $query->where('name', 'like', '%' . $filters->searchQuery . '%');
        }

        // Apply category filter
        if ($filters->categoryId) {
            $query->where('product_category_id', $filters->categoryId);
        }

        // Apply partner filter
        if ($filters->partnerId) {
            $query->where('partner_id', $filters->partnerId);
        }

        // Apply featured filter
        if ($filters->featured) {
            $query->where('is_featured', true);
        }

        return $query
            ->orderByDesc('created_at')
            ->paginate($filters->perPage)
            ->withQueryString();
    }

    /**
     * Resolve category from slug or return default.
     */
    public function resolveCategory(?string $categorySlug, bool $isFeatured = false): object
    {
        if ($categorySlug) {
            $category = ProductCategory::where('slug', $categorySlug)
                ->where('is_active', true)
                ->first();

            if ($category) {
                return (object) [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                ];
            }
        }

        return (object) [
            'name' => $isFeatured ? 'Featured Products' : 'All Products',
            'slug' => null,
        ];
    }

    /**
     * Get active categories for filters.
     */
    public function getActiveCategories(): Collection
    {
        return ProductCategory::where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'slug']);
    }

    /**
     * Get active partners for filters.
     */
    public function getActivePartners(): Collection
    {
        return Partner::where('status', 'active')
            ->orderBy('name')
            ->get(['id', 'name']);
    }

    /**
     * Get products for comparison.
     */
    public function getProductsForComparison(array $productIds): Collection
    {
        if (empty($productIds)) {
            return collect();
        }

        return Product::query()
            ->with('partner')
            ->whereIn('id', $productIds)
            ->get();
    }

    /**
     * Build comparison data structure.
     */
    public function buildComparisonData(Collection $products): array
    {
        $features = collect();
        $values = [];

        $productsData = $products->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'image_url' => $product->image_url ?? null,
                'partner' => $product->partner ? [
                    'id' => $product->partner->id,
                    'name' => $product->partner->name,
                    'logo_url' => $product->partner->logo_url ?? null,
                ] : null,
            ];
        })->values()->all();

        foreach ($products as $product) {
            $attributeValues = $product->attributeValues()->with('attribute')->get();

            foreach ($attributeValues as $attributeValue) {
                $key = (string) $attributeValue->attribute->id;
                $features[$key] = [
                    'key' => $key,
                    'label' => $attributeValue->attribute->name,
                ];

                // Handle provider attribute - show partner name instead of ID
                $attributeName = strtolower($attributeValue->attribute->name ?? '');
                $attributeSlug = strtolower($attributeValue->attribute->slug ?? '');

                if ($attributeName === 'provider' || $attributeSlug === 'provider') {
                    $values[$product->id][$key] = $product->partner->name ?? '—';
                } else {
                    $values[$product->id][$key] = $attributeValue->value_text
                        ?? $attributeValue->value_number
                        ?? ($attributeValue->value_boolean ? 'Yes' : 'No')
                        ?? ($attributeValue->value_json ? json_encode($attributeValue->value_json) : null);
                }
            }
        }

        return [
            'productsData' => $productsData,
            'features' => $features->values()->all(),
            'featuresData' => array_values(array_map(function ($f) {
                return [
                    'key' => $f['key'] ?? $f->key ?? null,
                    'label' => $f['label'] ?? $f->label ?? null,
                ];
            }, $features->values()->all())),
            'values' => $values,
        ];
    }
}

