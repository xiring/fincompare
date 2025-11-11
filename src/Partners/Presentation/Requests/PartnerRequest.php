<?php
namespace Src\Partners\Presentation\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * PartnerRequest form request.
 *
 * @package Src\Partners\Presentation\Requests
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
            'name' => ['required','string','max:255'],
            'slug' => ['nullable','string','max:255','unique:partners,slug'.($id?','.$id:'')],
            'logo_path' => ['nullable','string','max:1024'],
            'website_url' => ['nullable','url','max:255'],
            'contact_email' => ['nullable','email','max:255'],
            'contact_phone' => ['nullable','string','max:50'],
            'status' => ['required','in:active,inactive'],
        ];
    }
}


