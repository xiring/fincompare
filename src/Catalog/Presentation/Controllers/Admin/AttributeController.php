<?php

namespace Src\Catalog\Presentation\Controllers\Admin;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Src\Catalog\Application\Actions\CreateAttributeAction;
use Src\Catalog\Application\Actions\DeleteAttributeAction;
use Src\Catalog\Application\Actions\GetAttributesByCategoryAction;
use Src\Catalog\Application\Actions\UpdateAttributeAction;
use Src\Catalog\Application\DTOs\AttributeDTO;
use Src\Catalog\Domain\Entities\Attribute;
use Src\Catalog\Domain\Entities\ProductCategory;
use Src\Catalog\Presentation\Requests\AttributeRequest;

/**
 * AttributeController controller.
 */
class AttributeController extends Controller
{
    use AuthorizesRequests;

    public function __construct()
    {
        $this->authorizeResource(Attribute::class, 'attribute');

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $query = Attribute::query()->with('productCategory')
            ->when($request->get('product_category_id'), fn ($q, $cid) => $q->where('product_category_id', $cid))
            ->when($request->get('q'), fn ($q, $qStr) => $q->where('name', 'like', '%'.$qStr.'%'))
            ->orderBy('product_category_id')->orderBy('sort_order');
        $items = $query->paginate(20);
        
        return response()->json($items);

        $categories = ProductCategory::orderBy('name')->get(['id', 'name']);

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        
        return response()->json(['message' => 'Provide attribute payload to store.']);

        $categories = ProductCategory::orderBy('name')->get(['id', 'name']);

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(AttributeRequest $request, CreateAttributeAction $create)
    {
        $attr = $create->execute(AttributeDTO::fromArray($request->validated()));
        
        return response()->json($attr, 201);

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Attribute $attribute)
    {
        
        return response()->json($attribute);

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(AttributeRequest $request, Attribute $attribute, UpdateAttributeAction $update)
    {
        $attr = $update->execute($attribute, AttributeDTO::fromArray($request->validated()));
        
        return response()->json($attr);

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Attribute $attribute, DeleteAttributeAction $delete)
    {
        $delete->execute($attribute);
        
        return response()->json(null, 204);

        return back()->with('status', 'Attribute deleted');

    /**
     * Handle By category.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function byCategory(ProductCategory $product_category, GetAttributesByCategoryAction $byCategory)
    {
        return response()->json($byCategory->execute($product_category->id));

}
