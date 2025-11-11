<?php
namespace Src\Auth\Presentation\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * UpdatePasswordRequest form request.
 *
 * @package Src\Auth\Presentation\Requests
 */
class UpdatePasswordRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'current_password' => ['required','string'],
            'password' => ['required','confirmed','min:8'],
        ];
    }
}


