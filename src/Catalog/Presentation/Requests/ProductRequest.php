<?php

namespace Src\Catalog\Presentation\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * ProductRequest form request.
 */
class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Get the product ID from route parameter (can be 'id' or 'product' depending on route)
        $id = $this->route('id') ?? $this->route('product')?->id ?? null;

        return [
            'partner_id' => ['required', 'integer', 'exists:partners,id'],
            'product_category_id' => ['required', 'integer', 'exists:product_categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('products', 'slug')->ignore($id)],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:5120'], // 5MB
            'is_featured' => ['sometimes', 'boolean'],
            'status' => ['required', 'in:active,inactive'],
            'attributes' => ['sometimes'],
        ];
    }
}
