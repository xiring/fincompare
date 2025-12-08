<?php

namespace Src\Auth\Presentation\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * AdminUserUpdateRequest form request.
 */
class AdminUserUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Get the user ID from route parameter (can be 'id' or 'user' depending on route)
        $id = $this->route('id') ?? $this->route('user')?->id ?? null;

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($id)],
            'password' => ['nullable', 'min:8', 'confirmed'],
            'roles' => ['sometimes', 'array'],
        ];
    }
}
