<?php

namespace App\Http\Requests\Public;

use Illuminate\Foundation\Http\FormRequest;

/**
 * ReviewFormRequest — validates guest review submission.
 *
 * Legacy: Validator::make in PageController::reviewSubmit
 */
class ReviewFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email'       => 'required|email',
            'name'        => 'required|string|max:191',
            'message'     => 'required|string',
            'score'       => 'nullable|integer|min:1|max:5',
            'property_id' => [
                'nullable',
                'integer',
                function ($attribute, $value, $fail) {
                    $existsInProperties = \App\Models\Property::where('id', $value)->exists();
                    $existsInGuesty     = \App\Models\GuestyProperty::where('id', $value)->exists();
                    if (! $existsInProperties && ! $existsInGuesty) {
                        $fail('The selected property is invalid.');
                    }
                },
            ],
            'stay_date'   => 'nullable|date',
        ];
    }
}
