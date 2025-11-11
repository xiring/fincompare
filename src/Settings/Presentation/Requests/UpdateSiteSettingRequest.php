<?php
namespace Src\Settings\Presentation\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * UpdateSiteSettingRequest form request.
 *
 * @package Src\Settings\Presentation\Requests
 */
class UpdateSiteSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'site_name' => ['required','string','max:255'],
            'site_slogon' => ['nullable','string','max:255'],
            'favicon' => ['nullable','file','mimes:png,jpg,jpeg,webp,ico,svg','max:3072'],
            'logo' => ['nullable','file','mimes:png,jpg,jpeg,webp,ico,svg','max:4096'],
            'seo_titl' => ['nullable','string','max:255'],
            'seo_keyword' => ['nullable','string','max:255'],
            'seo_description' => ['nullable','string','max:255'],
            'email_address' => ['nullable','string','max:255'],
            'contact_number' => ['nullable','string','max:255'],
            'address' => ['nullable','string','max:255'],
            'map_url' => ['nullable','string','max:255'],
            'facebook_url' => ['nullable','string','max:255'],
            'instgram_url' => ['nullable','string','max:255'],
            'twitter_url' => ['nullable','string','max:255'],
        ];
    }
}


