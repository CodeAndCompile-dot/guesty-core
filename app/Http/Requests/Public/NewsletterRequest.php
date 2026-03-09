<?php

namespace App\Http\Requests\Public;

use Illuminate\Foundation\Http\FormRequest;

/**
 * NewsletterRequest — validates newsletter subscription (AJAX).
 *
 * Legacy: Validator::make in PageController::newsletterPost
 * Returns JSON on failure since this is an AJAX endpoint.
 */
class NewsletterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'required|email|unique:newsletters,email',
        ];
    }

    /**
     * Return JSON validation errors for AJAX requests.
     */
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator): void
    {
        throw new \Illuminate\Validation\ValidationException(
            $validator,
            response()->json([
                'status'  => 400,
                'message' => $validator->errors()->first(),
            ]),
        );
    }
}
