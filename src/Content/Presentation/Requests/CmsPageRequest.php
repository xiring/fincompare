<?php
namespace Src\Content\Presentation\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CmsPageRequest extends FormRequest
{
    public function authorize(): bool { return $this->user() !== null; }

    public function rules(): array
    {
        $id = optional($this->route('cms_page'))->id ?? null;
        return [
            'title' => ['required','string','max:255'],
            'slug' => ['nullable','string','max:255', Rule::unique('cms_pages','slug')->ignore($id)],
            'seo_title' => ['nullable','string','max:255'],
            'seo_description' => ['nullable','string'],
            'seo_keywords' => ['nullable','string'],
            'content' => ['nullable','string'],
            'status' => ['required','string','in:draft,published'],
        ];
    }
}


