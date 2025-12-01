<?php

namespace Src\Forms\Presentation\Controllers\Admin;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Src\Catalog\Domain\Entities\ProductCategory;
use Src\Forms\Application\Actions\CreateFormAction;
use Src\Forms\Application\Actions\DeleteFormAction;
use Src\Forms\Application\Actions\ListFormsAction;
use Src\Forms\Application\Actions\ShowFormAction;
use Src\Forms\Application\Actions\UpdateFormAction;
use Src\Forms\Application\DTOs\FormDTO;
use Src\Forms\Domain\Entities\Form;
use Src\Forms\Presentation\Requests\DynamicFormRequest;

/**
 * FormController controller.
 */
class FormController extends Controller
{
    use AuthorizesRequests;

    public function __construct()
    {
        $this->authorizeResource(Form::class, 'form');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request, ListFormsAction $list)
    {
        $filters = [
            'q' => $request->get('q'),
            'status' => $request->get('status'),
            'type' => $request->get('type'),
            'product_category_id' => $request->get('product_category_id'),
        ];
        $items = $list->execute($filters, (int) $request->get('per_page', 20));

        if ($request->wantsJson()) {
            return response()->json($items);
        }

        $categories = ProductCategory::orderBy('name')->get(['id', 'name']);

        return view('admin.forms.index', compact('items', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Provide form payload to store.']);
        }

        $categories = ProductCategory::orderBy('name')->get(['id', 'name']);

        return view('admin.forms.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(DynamicFormRequest $request, CreateFormAction $create)
    {
        $validated = $request->validated();
        $inputs = $validated['inputs'] ?? [];
        unset($validated['inputs']);

        // Convert options_text to options array for dropdown inputs
        foreach ($inputs as &$input) {
            if (isset($input['options_text']) && ! empty($input['options_text'])) {
                $input['options'] = array_filter(array_map('trim', explode("\n", $input['options_text'])));
                unset($input['options_text']);
            }
        }

        $form = $create->execute(FormDTO::fromArray($validated), $inputs);

        if ($request->wantsJson()) {
            return response()->json($form, 201);
        }

        return redirect()->route('admin.forms.index')->with('status', 'Form created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Form $form, ShowFormAction $show)
    {
        $form = $show->execute($form->id);

        if (request()->wantsJson()) {
            return response()->json($form);
        }

        return view('admin.forms.show', compact('form'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Form $form, ShowFormAction $show)
    {
        $form = $show->execute($form->id);

        if (request()->wantsJson()) {
            return response()->json($form);
        }

        return view('admin.forms.edit', compact('form'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(DynamicFormRequest $request, Form $form, UpdateFormAction $update)
    {
        $validated = $request->validated();
        $inputs = $validated['inputs'] ?? [];
        unset($validated['inputs']);

        // Convert options_text to options array for dropdown inputs
        foreach ($inputs as &$input) {
            if (isset($input['options_text']) && ! empty($input['options_text'])) {
                $input['options'] = array_filter(array_map('trim', explode("\n", $input['options_text'])));
                unset($input['options_text']);
            }
        }

        $form = $update->execute($form, FormDTO::fromArray($validated), $inputs);

        if ($request->wantsJson()) {
            return response()->json($form);
        }

        return redirect()->route('admin.forms.index')->with('status', 'Form updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Form $form, DeleteFormAction $delete)
    {
        $delete->execute($form);

        if (request()->wantsJson()) {
            return response()->json(null, 204);
        }

        return redirect()->route('admin.forms.index')->with('status', 'Form deleted successfully');
    }
}

