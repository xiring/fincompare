<?php
namespace Src\Catalog\Presentation\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('product_category')?->id ?? null;
        return [
            'name' => ['required','string','max:255'],
            'slug' => ['nullable','string','max:255','unique:product_categories,slug'.($id?','.$id:'')],
            'description' => ['nullable','string'],
            'is_active' => ['sometimes','boolean'],
        ];
    }
}


