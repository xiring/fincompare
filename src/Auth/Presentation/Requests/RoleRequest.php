<?php

namespace Src\Auth\Presentation\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
        // Get the role ID from route parameter (can be 'id' or 'role' depending on route)
        $id = $this->route('id') ?? $this->route('role')?->id ?? null;

        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('roles', 'name')->ignore($id)],
            'permissions' => ['sometimes', 'array'],
        ];
    }
}
