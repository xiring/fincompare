<?php

namespace Src\Partners\Presentation\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * PartnerRequest form request.
 */
class PartnerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('partner')?->id ?? null;

        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:partners,slug'.($id ? ','.$id : '')],
            'logo' => ['nullable', 'image', 'max:2048', 'mimes:jpeg,png,jpg,gif,webp'],
            'website_url' => ['nullable', 'url', 'max:255'],
            'contact_email' => ['nullable', 'email', 'max:255'],
            'contact_phone' => ['nullable', 'string', 'max:50'],
            'status' => ['required', 'in:active,inactive'],
        ];
    }
}
