<?php

namespace Src\Catalog\Presentation\Controllers\Admin;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
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
            'sort' => $request->get('sort'),
            'dir' => $request->get('dir'),
        ], (int) $request->get('per_page', 20));
        if ($request->wantsJson()) {
            return response()->json($items);
        }

        return view('admin.product_categories.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Provide product category payload to store.']);
        }

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

        return view('admin.product_categories.create', compact('preForms', 'postForms'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ProductCategoryRequest $request, CreateProductCategoryAction $create)
    {
        $item = $create->execute(ProductCategoryDTO::fromArray($request->validated()));
        if ($request->wantsJson()) {
            return response()->json($item, 201);
        }

        return redirect()->route('admin.product-categories.index')->with('status', 'Category created');
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, ProductCategory $product_category, ShowProductCategoryAction $show)
    {
        $product_category = $show->execute($product_category);
        if ($request->wantsJson()) {
            return response()->json($product_category);
        }

        return view('admin.product_categories.edit', compact('product_category'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Request $request, ProductCategory $product_category, ShowProductCategoryAction $show)
    {
        $product_category = $show->execute($product_category);
        if ($request->wantsJson()) {
            return response()->json($product_category);
        }

        // Get all pre_forms (including the one already assigned to this category)
        $assignedPreFormIds = \Src\Catalog\Domain\Entities\ProductCategory::whereNotNull('pre_form_id')
            ->where('id', '!=', $product_category->id)
            ->pluck('pre_form_id')
            ->toArray();
        $preForms = Form::where('type', 'pre_form')
            ->where(function($q) use ($product_category, $assignedPreFormIds) {
                $q->whereNotIn('id', $assignedPreFormIds);
                if ($product_category->pre_form_id) {
                    $q->orWhere('id', $product_category->pre_form_id);
                }
            })
            ->orderBy('name')
            ->get(['id', 'name']);

        // Get all post_forms (including the one already assigned to this category)
        $assignedPostFormIds = \Src\Catalog\Domain\Entities\ProductCategory::whereNotNull('post_form_id')
            ->where('id', '!=', $product_category->id)
            ->pluck('post_form_id')
            ->toArray();
        $postForms = Form::where('type', 'post_form')
            ->where(function($q) use ($product_category, $assignedPostFormIds) {
                $q->whereNotIn('id', $assignedPostFormIds);
                if ($product_category->post_form_id) {
                    $q->orWhere('id', $product_category->post_form_id);
                }
            })
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('admin.product_categories.edit', compact('product_category', 'preForms', 'postForms'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ProductCategoryRequest $request, ProductCategory $product_category, UpdateProductCategoryAction $update)
    {
        $item = $update->execute($product_category, ProductCategoryDTO::fromArray($request->validated()));
        if ($request->wantsJson()) {
            return response()->json($item);
        }

        return redirect()->route('admin.product-categories.index')->with('status', 'Category updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, ProductCategory $product_category, DeleteProductCategoryAction $delete)
    {
        $delete->execute($product_category);
        if ($request->wantsJson()) {
            return response()->json(null, 204);
        }

        return redirect()->route('admin.product-categories.index')->with('status', 'Category deleted');
    }
}
