<?php

namespace Src\Catalog\Presentation\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
        return [
            'partner_id' => ['required', 'integer', 'exists:partners,id'],
            'product_category_id' => ['required', 'integer', 'exists:product_categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:products,slug'.($this->route('product') ? ','.$this->route('product')->id : '')],
            'description' => ['nullable', 'string'],
            'is_featured' => ['sometimes', 'boolean'],
            'status' => ['required', 'in:active,inactive'],
            'attributes' => ['sometimes'],
        ];
    }
}
