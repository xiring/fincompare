<?php

namespace Src\Content\Presentation\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

/**
 * WysiwygImageUploadRequest form request.
 */
class WysiwygImageUploadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'image' => ['required', 'file', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:5120'],
        ];
    }

    protected function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $v) {
            if (! $this->hasFile('image')) {
                return;
            }
            $file = $this->file('image');
            try {
                $path = $file->getRealPath();
                if (! $path || ! @getimagesize($path)) {
                    $v->errors()->add('image', 'The uploaded file is not a valid image.');
                }
            } catch (\Throwable $e) {
                $v->errors()->add('image', 'Unable to verify the uploaded image.');
            }
        });
    }
}
