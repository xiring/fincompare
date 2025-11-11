<?php
namespace Src\Catalog\Presentation\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * ProductImportRequest form request.
 *
 * @package Src\Catalog\Presentation\Requests
 */
class ProductImportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null; // assume authenticated admin routes
    }

    public function rules(): array
    {
        return [
            'file' => ['required','file','mimes:csv,txt','max:20480'],
            'delimiter' => ['nullable','string', Rule::in([',',';','|',"\t","\\t"])],
            'has_header' => ['nullable','boolean'],
        ];
    }
}


