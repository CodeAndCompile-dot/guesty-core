<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UserFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('user');

        $emailRule = $id
            ? "required|unique:users,email,{$id}"
            : 'required|unique:users,email';

        $rules = [
            'email' => $emailRule,
            'name' => 'required',
        ];

        // Password is only required on create
        if (! $id) {
            $rules['password'] = 'required';
        }

        return $rules;
    }
}
