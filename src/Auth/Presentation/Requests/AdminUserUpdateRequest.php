<?php
namespace Src\Auth\Presentation\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminUserUpdateRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $id = $this->route('user')?->id;
        return [
            'name' => ['required','string','max:255'],
            'email' => ['required','email','max:255','unique:users,email'.($id?','.$id:'')],
            'password' => ['nullable','min:8','confirmed'],
            'roles' => ['sometimes','array'],
        ];
    }
}


