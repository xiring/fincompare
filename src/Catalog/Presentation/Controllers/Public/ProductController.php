<?php

namespace Src\Catalog\Presentation\Controllers\Public;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Src\Catalog\Application\DTOs\ProductListFiltersDTO;
use Src\Catalog\Application\Services\PublicProductService;
use Src\Catalog\Domain\Entities\Product;
use Src\Catalog\Domain\Entities\ProductCategory;
use Src\Shared\Presentation\Resources\CategoryResource;
use Src\Shared\Presentation\Resources\PartnerResource;
use Src\Shared\Presentation\Resources\ProductResource;

/**
 * ProductController - Handles public-facing product operations.
 */
class ProductController extends Controller
{
    public function __construct(
        private readonly PublicProductService $productService
    ) {}

    /**
     * Display a listing of products with optional filters.
     */
    public function index(Request $request): JsonResponse
    {
        $filters = ProductListFiltersDTO::fromRequest($request->all());

        // Resolve category if slug provided
        if ($filters->categorySlug) {
            $category = ProductCategory::where('slug', $filters->categorySlug)
                ->where('is_active', true)
                ->first();

            if ($category) {
                $filters = new ProductListFiltersDTO(
                    searchQuery: $filters->searchQuery,
                    categorySlug: $filters->categorySlug,
                    categoryId: $category->id,
                    partnerId: $filters->partnerId,
                    featured: $filters->featured,
                    perPage: $filters->perPage
                );
            }
        }

        $products = $this->productService->getPaginatedProducts($filters);
        $category = $this->productService->resolveCategory($filters->categorySlug, $filters->featured);
        $categories = $this->productService->getActiveCategories();
        $partners = $this->productService->getActivePartners();

        return response()->json([
            'products' => [
                'data' => ProductResource::collection($products),
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
                'next_page_url' => $products->nextPageUrl(),
                'prev_page_url' => $products->previousPageUrl(),
            ],
            'category' => $category,
            'categories' => CategoryResource::collection($categories),
            'partners' => PartnerResource::collection($partners),
            'next' => $products->nextPageUrl(),
        ]);
    }

    /**
     * Display products for a specific category.
     */
    public function category(ProductCategory $category, Request $request): JsonResponse
    {
        $filters = ProductListFiltersDTO::fromRequest($request->all());

        // Override category ID with route model binding
        $filters = new ProductListFiltersDTO(
            searchQuery: $filters->searchQuery,
            categorySlug: $category->slug,
            categoryId: $category->id,
            partnerId: $filters->partnerId,
            featured: $filters->featured,
            perPage: $filters->perPage
        );

        $products = $this->productService->getPaginatedProducts($filters);
        $categories = $this->productService->getActiveCategories();
        $partners = $this->productService->getActivePartners();

        return response()->json([
            'products' => [
                'data' => ProductResource::collection($products),
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
                'next_page_url' => $products->nextPageUrl(),
                'prev_page_url' => $products->previousPageUrl(),
            ],
            'category' => (object) [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
                'image_url' => $category->image_url,
            ],
            'categories' => CategoryResource::collection($categories),
            'partners' => PartnerResource::collection($partners),
            'next' => $products->nextPageUrl(),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Product $product, Request $request)
    {
        $attributes = $product->attributeValues()->with('attribute')->get()->map(function ($av) {
            return [
                'name' => $av->attribute->name ?? 'Attribute',
                'value' => $av->value_text ?? $av->value_number ?? ($av->value_boolean ? 'Yes' : 'No') ?? ($av->value_json ? json_encode($av->value_json) : null),
            ];
        });

        return response()->json([
            'product' => new ProductResource($product->load('partner')),
            'attributes' => $attributes,
        ]);
    }

    /**
     * Get products for comparison.
     */
    public function compare(Request $request): JsonResponse
    {
        $productIds = $this->extractProductIds($request);
        $products = $this->productService->getProductsForComparison($productIds);
        $comparisonData = $this->productService->buildComparisonData($products);

        return response()->json([
            'products' => ProductResource::collection($products),
            'productsData' => $comparisonData['productsData'],
            'features' => $comparisonData['features'],
            'featuresData' => $comparisonData['featuresData'],
            'values' => $comparisonData['values'],
        ]);
    }

    /**
     * Extract and validate product IDs from request.
     */
    private function extractProductIds(Request $request): array
    {
        $ids = $request->get('products');

        if (is_string($ids)) {
            $ids = explode(',', $ids);
        }

        if (empty($ids)) {
            $ids = (array) session('compare_ids', []);
        }

        return array_filter(array_map('intval', (array) $ids));
    }

    /**
     * Handle Toggle compare.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggleCompare(Request $request)
    {
        $id = (int) $request->input('id');
        $selected = filter_var($request->input('selected'), FILTER_VALIDATE_BOOLEAN);
        $ids = (array) session('compare_ids', []);
        if ($selected && ! in_array($id, $ids)) {
            $ids[] = $id;
        }
        if (! $selected) {
            $ids = array_values(array_diff($ids, [$id]));
        }
        session(['compare_ids' => $ids]);

        return response()->json(['ok' => true, 'ids' => $ids]);
    }
}
