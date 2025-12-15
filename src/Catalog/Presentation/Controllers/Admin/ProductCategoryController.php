<?php

namespace Src\Catalog\Presentation\Controllers\Admin;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Src\Catalog\Application\Actions\CreateProductCategoryAction;
use Src\Catalog\Application\Actions\DeleteProductCategoryAction;
use Src\Catalog\Application\Actions\ListProductCategoriesAction;
use Src\Catalog\Application\Actions\ShowProductCategoryAction;
use Src\Catalog\Application\Actions\UpdateProductCategoryAction;
use Src\Catalog\Application\DTOs\ProductCategoryDTO;
use Src\Catalog\Domain\Entities\ProductCategory;
use Src\Catalog\Presentation\Requests\ProductCategoryRequest;
use Src\Forms\Domain\Entities\Form;

/**
 * ProductCategoryController controller.
 */
class ProductCategoryController extends Controller
{
    use AuthorizesRequests;

    public function __construct()
    {
        $this->authorizeResource(ProductCategory::class, 'product_category');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request, ListProductCategoriesAction $list)
    {
        $items = $list->execute([
            'q' => $request->get('q'),
            'group_id' => $request->get('group_id'),
            'sort' => $request->get('sort'),
            'dir' => $request->get('dir'),
        ], (int) $request->get('per_page', 20));

        return response()->json($items);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        return response()->json(['message' => 'Provide product category payload to store.']);

        // Get all pre_forms that are not already assigned to any category
        $assignedPreFormIds = \Src\Catalog\Domain\Entities\ProductCategory::whereNotNull('pre_form_id')
            ->pluck('pre_form_id')
            ->toArray();
        $preForms = Form::where('type', 'pre_form')
            ->whereNotIn('id', $assignedPreFormIds)
            ->orderBy('name')
            ->get(['id', 'name']);

        // Get all post_forms that are not already assigned to any category
        $assignedPostFormIds = \Src\Catalog\Domain\Entities\ProductCategory::whereNotNull('post_form_id')
            ->pluck('post_form_id')
            ->toArray();
        $postForms = Form::where('type', 'post_form')
            ->whereNotIn('id', $assignedPostFormIds)
            ->orderBy('name')
            ->get(['id', 'name']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ProductCategoryRequest $request, CreateProductCategoryAction $create)
    {
        $data = $request->validated();

        // Handle image upload
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('categories/'.now()->format('Y/m'), 'public');
        }

        $item = $create->execute(ProductCategoryDTO::fromArray($data));

        return response()->json($item, 201);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, int $id, ShowProductCategoryAction $show)
    {
        $product_category = ProductCategory::findOrFail($id);
        $this->authorize('view', $product_category);
        $product_category = $show->execute($product_category);

        return response()->json($product_category);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Request $request, int $id, ShowProductCategoryAction $show)
    {
        $product_category = ProductCategory::findOrFail($id);
        $this->authorize('update', $product_category);
        $product_category = $show->execute($product_category);

        return response()->json($product_category);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ProductCategoryRequest $request, int $id, UpdateProductCategoryAction $update)
    {
        $product_category = ProductCategory::findOrFail($id);
        $this->authorize('update', $product_category);

        $data = $request->validated();

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product_category->image && Storage::disk('public')->exists($product_category->image)) {
                Storage::disk('public')->delete($product_category->image);
            }

            $data['image'] = $request->file('image')->store('categories/'.now()->format('Y/m'), 'public');
        } else {
            // Keep existing image if not updated
            $data['image'] = $product_category->image;
        }

        $item = $update->execute($product_category, ProductCategoryDTO::fromArray($data));

        return response()->json($item);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, int $id, DeleteProductCategoryAction $delete)
    {
        $product_category = ProductCategory::findOrFail($id);
        $this->authorize('delete', $product_category);
        $delete->execute($product_category);

        return response()->json(null, 204);
    }
}
