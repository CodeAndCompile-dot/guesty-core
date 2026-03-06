<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class NewsLetterFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('newsletter');

        $uniqueRule = $id
            ? "required|unique:newsletters,email,{$id}"
            : 'required|unique:newsletters,email';

        return [
            'email' => $uniqueRule,
        ];
    }
}
