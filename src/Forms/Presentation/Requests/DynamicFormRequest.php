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

    protected function prepareForValidation(): void
    {
        // Convert JSON string options to arrays before validation
        if ($this->has('inputs') && is_array($this->inputs)) {
            $inputs = $this->inputs;
            foreach ($inputs as $key => $input) {
                // If options_text exists, use it (fallback if JavaScript didn't run)
                if (isset($input['options_text']) && !empty(trim($input['options_text']))) {
                    $options = array_filter(array_map('trim', explode("\n", $input['options_text'])));
                    $inputs[$key]['options'] = !empty($options) ? $options : null;
                    unset($inputs[$key]['options_text']);
                } elseif (isset($input['options'])) {
                    if (is_string($input['options'])) {
                        // Try to decode JSON string (from JavaScript hidden input)
                        $decoded = json_decode($input['options'], true);
                        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                            $inputs[$key]['options'] = !empty($decoded) ? $decoded : null;
                        } else {
                            // If not valid JSON, treat as empty
                            $inputs[$key]['options'] = null;
                        }
                    } elseif (is_array($input['options']) && empty($input['options'])) {
                        // Empty array should be null
                        $inputs[$key]['options'] = null;
                    }
                } else {
                    // If options is not set, set to null
                    $inputs[$key]['options'] = null;
                }
            }
            $this->merge(['inputs' => $inputs]);
        }
    }

    public function rules(): array
    {
        $id = $this->route('form')?->id ?? null;

        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:forms,slug'.($id ? ','.$id : '')],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:active,inactive'],
            'type' => ['required', 'in:pre_form,post_form'],
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

