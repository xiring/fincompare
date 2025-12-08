<?php

namespace Src\Auth\Presentation\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
        // Get the permission ID from route parameter (can be 'id' or 'permission' depending on route)
        $id = $this->route('id') ?? $this->route('permission')?->id ?? null;

        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('permissions', 'name')->ignore($id)],
        ];
    }
}
