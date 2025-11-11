<?php
namespace Src\Content\Presentation\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * BlogPostRequest form request.
 *
 * @package Src\Content\Presentation\Requests
 */
class BlogPostRequest extends FormRequest
{
    public function authorize(): bool { return $this->user() !== null; }

    public function rules(): array
    {
        $id = optional($this->route('blog'))->id ?? null;
        return [
            'title' => ['required','string','max:255'],
            'slug' => ['nullable','string','max:255', Rule::unique('blog_posts','slug')->ignore($id)],
            'category' => ['nullable','string','max:255'],
            'content' => ['nullable','string'],
            'featured_image' => ['nullable','string','max:255'],
            'status' => ['required','string','in:draft,published'],
            'seo_title' => ['nullable','string','max:255'],
            'seo_description' => ['nullable','string'],
            'seo_keywords' => ['nullable','string'],
            'tags' => ['nullable'],
        ];
    }
}


