<?php

namespace Src\Auth\Presentation\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * RoleRequest form request.
 */
class RoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('role')?->id;

        return [
            'name' => ['required', 'string', 'max:255', 'unique:roles,name'.($id ? ','.$id : '')],
            'permissions' => ['sometimes', 'array'],
        ];
    }
}
