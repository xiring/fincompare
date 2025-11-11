<?php
namespace Src\Auth\Presentation\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * UpdateProfileRequest form request.
 *
 * @package Src\Auth\Presentation\Requests
 */
class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $id = $this->user()?->id;
        return [
            'name' => ['required','string','max:255'],
            'email' => ['required','email','max:255','unique:users,email'.($id?','.$id:'')],
        ];
    }
}


