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
use Src\Catalog\Domain\Repositories\AdminProductRepositoryInterface;
use Src\Catalog\Presentation\Requests\ProductRequest;

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
        $criteria = \Src\Shared\Application\Criteria\ListCriteria::fromArray([
            'q' => $request->get('q'),
            'sort' => $request->get('sort'),
            'dir' => $request->get('dir'),
            'per_page' => $request->get('per_page', 20),
            'filters' => [
                'product_category_id' => $request->integer('product_category_id') ?: null,
                'partner_id' => $request->integer('partner_id') ?: null,
            ],
        ]);

        $items = $list->execute($criteria);
        return response()->json($items);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        return response()->json(['message' => 'Provide product payload to store.']);
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
                'attributes' => $data['attributes'] ?? [],
            ],
            attributesInput: $data['attributes'] ?? []
        );
        return response()->json($product->load(['partner', 'productCategory']), 201);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, int $id, ShowProductAction $show, AdminProductRepositoryInterface $repository)
    {
        $product = $repository->find($id);
        if (!$product) {
            abort(404);
        }
        $this->authorize('view', $product);
        $product = $show->execute($product);
        return response()->json($product);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Request $request, int $id, ShowProductAction $show, AdminProductRepositoryInterface $repository)
    {
        $product = $repository->find($id);
        if (!$product) {
            abort(404);
        }
        $this->authorize('update', $product);
        $product = $show->execute($product);
        return response()->json($product);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ProductRequest $request, int $id, UpdateProductAction $update, AdminProductRepositoryInterface $repository)
    {
        $product = $repository->find($id);
        if (!$product) {
            abort(404);
        }
        $this->authorize('update', $product);

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
        return response()->json($product->load(['partner', 'productCategory', 'attributeValues.attribute']));
    }

    /**
     * Duplicate the specified resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function duplicate(int $id, DuplicateProductAction $duplicate, AdminProductRepositoryInterface $repository)
    {
        $product = $repository->find($id);
        if (!$product) {
            abort(404);
        }
        $this->authorize('create', Product::class);
        $duplicatedProduct = $duplicate->execute($product);

        return response()->json($duplicatedProduct, 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, int $id, DeleteProductAction $delete, AdminProductRepositoryInterface $repository)
    {
        $product = $repository->find($id);
        if (!$product) {
            abort(404);
        }
        $this->authorize('delete', $product);
        $delete->execute($product);
        return response()->json(null, 204);
    }

}
