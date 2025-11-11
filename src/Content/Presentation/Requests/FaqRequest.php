<?php
namespace Src\Content\Presentation\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * FaqRequest form request.
 *
 * @package Src\Content\Presentation\Requests
 */
class FaqRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'question' => ['required','string','max:255'],
            'answer' => ['required','string'],
        ];
    }
}


