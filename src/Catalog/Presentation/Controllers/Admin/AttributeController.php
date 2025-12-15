<?php

namespace Src\Catalog\Presentation\Controllers\Admin;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Src\Catalog\Application\Actions\CreateAttributeAction;
use Src\Catalog\Application\Actions\DeleteAttributeAction;
use Src\Catalog\Application\Actions\GetAttributesByCategoryAction;
use Src\Catalog\Application\Actions\ListAttributesAction;
use Src\Catalog\Application\Actions\UpdateAttributeAction;
use Src\Catalog\Application\DTOs\AttributeDTO;
use Src\Catalog\Domain\Entities\Attribute;
use Src\Catalog\Domain\Repositories\AttributeRepositoryInterface;
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
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request, ListAttributesAction $list)
    {
        $criteria = \Src\Shared\Application\Criteria\ListCriteria::fromArray([
            'q' => $request->get('q'),
            'sort' => $request->get('sort'),
            'dir' => $request->get('dir'),
            'per_page' => $request->get('per_page', 20),
            'filters' => [
                'product_category_id' => $request->integer('product_category_id') ?: null,
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
        return response()->json(['message' => 'Provide attribute payload to store.']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(AttributeRequest $request, CreateAttributeAction $create)
    {
        $attr = $create->execute(AttributeDTO::fromArray($request->validated()));

        return response()->json($attr, 201);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(int $id, AttributeRepositoryInterface $repository)
    {
        $attribute = $repository->find($id);
        if (!$attribute) {
            abort(404);
        }
        $this->authorize('update', $attribute);
        return response()->json($attribute->load('productCategory'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(AttributeRequest $request, int $id, UpdateAttributeAction $update, AttributeRepositoryInterface $repository)
    {
        $attribute = $repository->find($id);
        if (!$attribute) {
            abort(404);
        }
        $this->authorize('update', $attribute);
        $attr = $update->execute($attribute, AttributeDTO::fromArray($request->validated()));

        return response()->json($attr);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $id, DeleteAttributeAction $delete, AttributeRepositoryInterface $repository)
    {
        $attribute = $repository->find($id);
        if (!$attribute) {
            abort(404);
        }
        $this->authorize('delete', $attribute);
        $delete->execute($attribute);

        return response()->json(null, 204);
    }

    /**
     * Handle By category.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function byCategory(int $id, GetAttributesByCategoryAction $byCategory)
    {
        return response()->json($byCategory->execute($id));
    }
}
