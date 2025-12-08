<?php

namespace Src\Catalog\Presentation\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * AttributeRequest form request.
 */
class AttributeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Get the attribute ID from route parameter (can be 'id' or 'attribute' depending on route)
        $id = $this->route('id') ?? $this->route('attribute')?->id ?? null;

        return [
            'product_category_id' => ['required', 'integer', 'exists:product_categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('attributes', 'slug')->ignore($id)],
            'data_type' => ['required', 'in:text,number,percentage,boolean,json'],
            'unit' => ['nullable', 'string', 'max:50'],
            'is_filterable' => ['sometimes', 'boolean'],
            'is_required' => ['sometimes', 'boolean'],
            'sort_order' => ['sometimes', 'integer', 'min:0'],
        ];
    }
}
