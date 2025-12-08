<?php

namespace Src\Forms\Presentation\Controllers\Admin;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Src\Forms\Application\Actions\CreateFormAction;
use Src\Forms\Application\Actions\DeleteFormAction;
use Src\Forms\Application\Actions\DuplicateFormAction;
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
            'sort' => $request->get('sort'),
            'dir' => $request->get('dir'),
        ];
        $items = $list->execute($filters, (int) $request->get('per_page', 20));

        return response()->json($items);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {

        return response()->json(['message' => 'Provide form payload to store.']);
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

        // Convert options_text to options array for dropdown inputs (fallback if JavaScript didn't run)
        foreach ($inputs as &$input) {
            if (isset($input['options_text']) && ! empty($input['options_text'])) {
                $input['options'] = array_filter(array_map('trim', explode("\n", $input['options_text'])));
                unset($input['options_text']);
            }
            // Ensure options is null if empty array (for non-dropdown inputs)
            if (isset($input['options']) && is_array($input['options']) && empty($input['options'])) {
                $input['options'] = null;
            }
        }

        $form = $create->execute(FormDTO::fromArray($validated), $inputs);

        return response()->json($form, 201);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Form $form, ShowFormAction $show)
    {
        $form = $show->execute($form->id);

        return response()->json($form);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Form $form, ShowFormAction $show)
    {
        $form = $show->execute($form->id);

        return response()->json($form);
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

        // Convert options_text to options array for dropdown inputs (fallback if JavaScript didn't run)
        foreach ($inputs as &$input) {
            if (isset($input['options_text']) && ! empty($input['options_text'])) {
                $input['options'] = array_filter(array_map('trim', explode("\n", $input['options_text'])));
                unset($input['options_text']);
            }
            // Ensure options is null if empty array (for non-dropdown inputs)
            if (isset($input['options']) && is_array($input['options']) && empty($input['options'])) {
                $input['options'] = null;
            }
        }

        $form = $update->execute($form, FormDTO::fromArray($validated), $inputs);

        return response()->json($form);
    }

    /**
     * Duplicate the specified resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function duplicate(Form $form, DuplicateFormAction $duplicate)
    {
        $duplicatedForm = $duplicate->execute($form);

        return response()->json($duplicatedForm, 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Form $form, DeleteFormAction $delete)
    {
        $delete->execute($form);

        return response()->json(null, 204);
    }
}

