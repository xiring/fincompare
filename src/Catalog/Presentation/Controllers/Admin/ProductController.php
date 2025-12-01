<?php

namespace Src\Catalog\Presentation\Controllers\Admin;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Src\Catalog\Application\Actions\CreateProductAction;
use Src\Catalog\Application\Actions\DeleteProductAction;
use Src\Catalog\Application\Actions\DuplicateProductAction;
use Src\Catalog\Application\Actions\ListProductsAction;
use Src\Catalog\Application\Actions\ShowProductAction;
use Src\Catalog\Application\Actions\UpdateProductAction;
use Src\Catalog\Domain\Entities\Product;
use Src\Catalog\Domain\Entities\ProductCategory;
use Src\Catalog\Presentation\Requests\ProductRequest;
use Src\Partners\Domain\Entities\Partner;

/**
 * ProductController controller.
 */
class ProductController extends Controller
{
    use AuthorizesRequests;

    public function __construct()
    {
        $this->authorizeResource(Product::class, 'product');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request, ListProductsAction $list)
    {
        $items = $list->execute([
            'q' => $request->get('q'),
            'sort' => $request->get('sort'),
            'dir' => $request->get('dir'),
        ], (int) $request->get('per_page', 20));
        if ($request->wantsJson()) {
            return response()->json($items);
        }

        return view('admin.products.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Provide product payload to store.']);
        }
        $partners = Partner::orderBy('name')->get(['id', 'name']);
        $categories = ProductCategory::orderBy('name')->get(['id', 'name']);

        return view('admin.products.create', compact('partners', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ProductRequest $request, CreateProductAction $create)
    {
        $data = $request->validated();
        if (isset($data['attributes']) && is_string($data['attributes'])) {
            $decoded = json_decode($data['attributes'], true);
            $data['attributes'] = is_array($decoded) ? $decoded : [];
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products/'.now()->format('Y/m'), 'public');
        }

        $product = $create->execute(
            data: [
                'partner_id' => $data['partner_id'],
                'product_category_id' => $data['product_category_id'],
                'name' => $data['name'],
                'slug' => $data['slug'] ?? null,
                'description' => $data['description'] ?? null,
                'image' => $data['image'] ?? null,
                'is_featured' => (bool) ($data['is_featured'] ?? false),
                'status' => $data['status'],
            ],
            attributesInput: $data['attributes'] ?? []
        );
        if ($request->wantsJson()) {
            return response()->json($product->load(['partner', 'productCategory']), 201);
        }

        return redirect()->route('admin.products.index')->with('status', 'Product created');
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, Product $product, ShowProductAction $show)
    {
        $product = $show->execute($product);
        if ($request->wantsJson()) {
            return response()->json($product);
        }
        $partners = Partner::orderBy('name')->get(['id', 'name']);
        $categories = ProductCategory::orderBy('name')->get(['id', 'name']);

        return view('admin.products.edit', compact('product', 'partners', 'categories'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Request $request, Product $product, ShowProductAction $show)
    {
        $product = $show->execute($product);
        if ($request->wantsJson()) {
            return response()->json($product);
        }
        $partners = Partner::orderBy('name')->get(['id', 'name']);
        $categories = ProductCategory::orderBy('name')->get(['id', 'name']);

        return view('admin.products.edit', compact('product', 'partners', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ProductRequest $request, Product $product, UpdateProductAction $update)
    {
        $data = $request->validated();
        if (isset($data['attributes']) && is_string($data['attributes'])) {
            $decoded = json_decode($data['attributes'], true);
            $data['attributes'] = is_array($decoded) ? $decoded : [];
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products/'.now()->format('Y/m'), 'public');
        }

        $product = $update->execute(
            product: $product,
            data: [
                'partner_id' => $data['partner_id'],
                'product_category_id' => $data['product_category_id'],
                'name' => $data['name'],
                'slug' => $data['slug'] ?? null,
                'description' => $data['description'] ?? null,
                'image' => $data['image'] ?? $product->image, // Keep existing image if not updated
                'is_featured' => (bool) ($data['is_featured'] ?? false),
                'status' => $data['status'],
            ],
            attributesInput: $data['attributes'] ?? []
        );
        if ($request->wantsJson()) {
            return response()->json($product->load(['partner', 'productCategory', 'attributeValues.attribute']));
        }

        return redirect()->route('admin.products.index')->with('status', 'Product updated');
    }

    /**
     * Duplicate the specified resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function duplicate(Product $product, DuplicateProductAction $duplicate)
    {
        $duplicatedProduct = $duplicate->execute($product);

        if (request()->wantsJson()) {
            return response()->json($duplicatedProduct, 201);
        }

        return redirect()->route('admin.products.index')->with('status', 'Product duplicated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, Product $product, DeleteProductAction $delete)
    {
        $delete->execute($product);
        if ($request->wantsJson()) {
            return response()->json(null, 204);
        }

        return redirect()->route('admin.products.index')->with('status', 'Product deleted');
    }
}
