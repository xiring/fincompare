<?php

namespace Src\Forms\Presentation\Requests;

use Illuminate\Foundation\Http\FormRequest as BaseFormRequest;

/**
 * DynamicFormRequest form request.
 */
class DynamicFormRequest extends BaseFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('form')?->id ?? null;

        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:forms,slug'.($id ? ','.$id : '')],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:active,inactive'],
            'inputs' => ['sometimes', 'array'],
            'inputs.*.label' => ['required_with:inputs', 'string', 'max:255'],
            'inputs.*.name' => ['required_with:inputs', 'string', 'max:255', 'regex:/^[a-z0-9_]+$/'],
            'inputs.*.type' => ['required_with:inputs', 'in:text,dropdown,checkbox,textarea'],
            'inputs.*.options' => ['nullable', 'array'],
            'inputs.*.placeholder' => ['nullable', 'string', 'max:255'],
            'inputs.*.help_text' => ['nullable', 'string'],
            'inputs.*.is_required' => ['sometimes', 'boolean'],
            'inputs.*.validation_rules' => ['nullable', 'string', 'max:255'],
            'inputs.*.sort_order' => ['sometimes', 'integer', 'min:0'],
        ];
    }
}

