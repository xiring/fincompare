<?php

namespace Src\Auth\Presentation\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * PermissionRequest form request.
 */
class PermissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('permission')?->id;

        return [
            'name' => ['required', 'string', 'max:255', 'unique:permissions,name'.($id ? ','.$id : '')],
        ];
    }
}
