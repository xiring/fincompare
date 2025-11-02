<?php
namespace Src\Catalog\Presentation\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Src\Catalog\Domain\Entities\ProductCategory;
use Src\Catalog\Presentation\Requests\ProductCategoryRequest;
use Src\Catalog\Application\Actions\ListProductCategoriesAction;
use Src\Catalog\Application\Actions\ShowProductCategoryAction;
use Src\Catalog\Application\Actions\CreateProductCategoryAction;
use Src\Catalog\Application\Actions\UpdateProductCategoryAction;
use Src\Catalog\Application\Actions\DeleteProductCategoryAction;
use Src\Catalog\Application\DTOs\ProductCategoryDTO;

class ProductCategoryController extends Controller
{
    use AuthorizesRequests;

    public function __construct()
    {
        $this->authorizeResource(ProductCategory::class, 'product_category');
    }

    public function index(Request $request, ListProductCategoriesAction $list)
    {
        $items = $list->execute([
            'q'=>$request->get('q'),
            'sort'=>$request->get('sort'),
            'dir'=>$request->get('dir')
        ], (int)$request->get('per_page', 20));
        if ($request->wantsJson()) return response()->json($items);
        return view('admin.product_categories.index', compact('items'));
    }

    public function create(Request $request)
    {
        if ($request->wantsJson()) return response()->json(['message' => 'Provide product category payload to store.']);
        return view('admin.product_categories.create');
    }

    public function store(ProductCategoryRequest $request, CreateProductCategoryAction $create)
    {
        $item = $create->execute(ProductCategoryDTO::fromArray($request->validated()));
        if ($request->wantsJson()) return response()->json($item, 201);
        return redirect()->route('admin.product-categories.index')->with('status', 'Category created');
    }

    public function show(Request $request, ProductCategory $product_category, ShowProductCategoryAction $show)
    {
        $product_category = $show->execute($product_category);
        if ($request->wantsJson()) return response()->json($product_category);
        return view('admin.product_categories.edit', compact('product_category'));
    }

    public function edit(Request $request, ProductCategory $product_category, ShowProductCategoryAction $show)
    {
        $product_category = $show->execute($product_category);
        if ($request->wantsJson()) return response()->json($product_category);
        return view('admin.product_categories.edit', compact('product_category'));
    }

    public function update(ProductCategoryRequest $request, ProductCategory $product_category, UpdateProductCategoryAction $update)
    {
        $item = $update->execute($product_category, ProductCategoryDTO::fromArray($request->validated()));
        if ($request->wantsJson()) return response()->json($item);
        return redirect()->route('admin.product-categories.index')->with('status', 'Category updated');
    }

    public function destroy(Request $request, ProductCategory $product_category, DeleteProductCategoryAction $delete)
    {
        $delete->execute($product_category);
        if ($request->wantsJson()) return response()->json(null, 204);
        return redirect()->route('admin.product-categories.index')->with('status', 'Category deleted');
    }
}


