<?php

namespace Src\Catalog\Presentation\Controllers\Public;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Src\Catalog\Domain\Entities\Product;
use Src\Catalog\Domain\Entities\ProductCategory;
use Src\Partners\Domain\Entities\Partner;

/**
 * ProductController controller.
 */
class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $categoryId = $request->integer('category_id');
        $partnerId = $request->integer('partner_id');
        $featured = $request->boolean('featured');

        $products = Product::query()->with(['partner'])
            ->where('status', 'active')
            ->when($request->get('q'), fn ($q, $s) => $q->where('name', 'like', '%'.$s.'%'))
            ->when($categoryId, fn ($q, $id) => $q->where('product_category_id', $id))
            ->when($partnerId, fn ($q, $id) => $q->where('partner_id', $id))
            ->when($featured, fn ($q) => $q->where('is_featured', true))
            ->orderByDesc('created_at')
            ->paginate(12)
            ->withQueryString();

        $category = $categoryId ? ProductCategory::find($categoryId) : (object) ['name' => $featured ? 'Featured Products' : 'All Products'];
        $category_attributes = [];
        $categories = ProductCategory::orderBy('name')->get(['id', 'name']);
        $partners = Partner::orderBy('name')->get(['id', 'name']);

        if ($request->wantsJson()) {
            $html = view()->file(base_path('src/Catalog/Presentation/Views/Public/_product_cards_chunk.blade.php'), [
                'products' => $products,
            ])->render();

            return response()->json([
                'html' => $html,
                'next' => $products->nextPageUrl(),
            ]);
        }

        return view()->file(base_path('src/Catalog/Presentation/Views/Public/category_listing.blade.php'), compact('products', 'category', 'category_attributes', 'categories', 'partners', 'featured'));
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application
     */
    public function show(Product $product)
    {
        $attributes = $product->attributeValues()->with('attribute')->get()->map(function ($av) {
            return (object) [
                'name' => $av->attribute->name ?? 'Attribute',
                'value' => $av->value_text ?? $av->value_number ?? ($av->value_boolean ? 'Yes' : 'No') ?? ($av->value_json ? json_encode($av->value_json) : null),
            ];
        });

        return view()->file(base_path('src/Catalog/Presentation/Views/Public/product_details.blade.php'), [
            'product' => $product->load('partner'),
            'attributes' => $attributes,
        ]);
    }

    /**
     * Handle Compare.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application
     */
    public function compare(Request $request)
    {
        $ids = $request->get('products');
        if (is_string($ids)) {
            $ids = array_filter(array_map('intval', explode(',', $ids)));
        }
        if (empty($ids)) {
            $ids = (array) session('compare_ids', []);
        }

        // Ensure we have valid IDs and filter out any invalid ones
        $ids = array_filter(array_map('intval', $ids));

                // If no valid IDs, return empty result
        if (empty($ids)) {
            $products = collect();
        } else {
            $products = Product::query()->with('partner')->whereIn('id', $ids)->get();
        }
        $features = collect();
        $values = [];

        // Prepare products data for frontend
        $productsData         = $products->map(function ($p) {
            return [
                'id' => $p->id,
                'name' => $p->name,
                'image' => $p->image_url ?? null,
                'logo' => $p->partner->logo_url ?? null,
            ];
        })->values()->all();

        foreach ($products as $p) {
            $avs = $p->attributeValues()->with('attribute')->get();
            foreach ($avs as $av) {
                $key = (string) $av->attribute->id;
                $features[$key] = ['key' => $key, 'label' => $av->attribute->name];

                // Check if this is a "provider" attribute - show partner name instead of ID
                $attributeName = strtolower($av->attribute->name ?? '');
                $attributeSlug = strtolower($av->attribute->slug ?? '');
                if ($attributeName === 'provider' || $attributeSlug === 'provider') {
                    $values[$p->id][$key] =         $p->partner->name ?? '—';
                } else {
                    $values[$p->id][$key] = $av->value_text ?? $av->value_number ?? ($av->value_boolean ? 'Yes' : 'No') ?? ($av->value_json ? json_encode($av->value_json) : null);
                }
            }
        }

        $featuresData = array_values(array_map(function ($f) {
            return [
                'key' => $f['key'] ?? $f->key ?? null,
                'label' => $f['label'] ?? $f->label ?? null,
            ];
        }, $features->values()->all()));

        return view()->file(base_path('src/Catalog/Presentation/Views/Public/compare_products.blade.php'), [
            'products' => $products,
            'productsData' => $productsData,
            'features' => $features->values()->all(),
            'featuresData' => $featuresData,
            'values' => $values,
        ]);
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
