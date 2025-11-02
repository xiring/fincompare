<?php
namespace Src\Leads\Presentation\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LeadUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => ['sometimes','string','max:50'],
            'message' => ['sometimes','nullable','string'],
            'product_id' => ['sometimes','nullable','integer','exists:products,id'],
        ];
    }
}


