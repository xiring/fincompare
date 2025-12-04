<?php

namespace Src\Shared\Presentation\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * StoreContactMessageRequest form request.
 */
class StoreContactMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'max:255', 'email:rfc,dns'],
            'message' => ['required', 'string', 'max:5000'],
            // Timestamp from form render, used to enforce a minimal submit time window
            'submitted_at' => ['required', 'integer'],
        ];
    }

    /**
     * Handle With validator.
     *
     * @param mixed $validator
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $submittedAt = (int) $this->input('submitted_at', 0);
            $age = now()->timestamp - $submittedAt;
            $minSeconds = (int) config('contact.min_submit_seconds', 3);
            // Require at least configured seconds between render and submit
            if ($submittedAt === 0 || $age < $minSeconds) {
                $validator->errors()->add('submitted_at', 'Please wait a moment before submitting the form.');
            }
        });
    }
}
