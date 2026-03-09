<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validates the Stripe payment form submission.
 *
 * Legacy had no server-side validation — just raw $request->stripeToken usage.
 */
class StripePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'stripeToken' => 'required|string',
            'amount'      => 'required|numeric|min:0.50',
        ];
    }

    public function messages(): array
    {
        return [
            'stripeToken.required' => 'A valid payment token is required.',
            'amount.required'      => 'Payment amount is required.',
            'amount.min'           => 'Minimum payment amount is $0.50.',
        ];
    }
}
