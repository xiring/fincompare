<?php

namespace Src\Catalog\Presentation\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * ProductCategoryRequest form request.
 */
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
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:product_categories,slug'.($id ? ','.$id : '')],
            'description' => ['nullable', 'string'],
            'is_active' => ['sometimes', 'boolean'],
            'pre_form_id' => ['nullable', 'integer', 'exists:forms,id', function ($attribute, $value, $fail) use ($id) {
                if ($value) {
                    $form = \Src\Forms\Domain\Entities\Form::find($value);
                    if ($form) {
                        if ($form->type !== 'pre_form') {
                            $fail('The selected form must be a pre_form.');
                        }
                        // Check if this form is already assigned to another category as pre_form
                        $existingCategory = \Src\Catalog\Domain\Entities\ProductCategory::where('pre_form_id', $value)
                            ->when($id, fn($q) => $q->where('id', '!=', $id))
                            ->first();
                        if ($existingCategory) {
                            $fail('The selected form is already assigned to another category.');
                        }
                    }
                }
            }],
            'post_form_id' => ['nullable', 'integer', 'exists:forms,id', function ($attribute, $value, $fail) use ($id) {
                if ($value) {
                    $form = \Src\Forms\Domain\Entities\Form::find($value);
                    if ($form) {
                        if ($form->type !== 'post_form') {
                            $fail('The selected form must be a post_form.');
                        }
                        // Check if this form is already assigned to another category as post_form
                        $existingCategory = \Src\Catalog\Domain\Entities\ProductCategory::where('post_form_id', $value)
                            ->when($id, fn($q) => $q->where('id', '!=', $id))
                            ->first();
                        if ($existingCategory) {
                            $fail('The selected form is already assigned to another category.');
                        }
                    }
                }
            }],
        ];
    }
}
