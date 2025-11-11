<?php
namespace Src\Catalog\Presentation\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * AttributeRequest form request.
 *
 * @package Src\Catalog\Presentation\Requests
 */
class AttributeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('attribute')?->id ?? null;
        return [
            'product_category_id' => ['required','integer','exists:product_categories,id'],
            'name' => ['required','string','max:255'],
            'slug' => ['nullable','string','max:255','unique:attributes,slug'.($id?','.$id:'')],
            'data_type' => ['required','in:text,number,percentage,boolean,json'],
            'unit' => ['nullable','string','max:50'],
            'is_filterable' => ['sometimes','boolean'],
            'is_required' => ['sometimes','boolean'],
            'sort_order' => ['sometimes','integer','min:0'],
        ];
    }
}


