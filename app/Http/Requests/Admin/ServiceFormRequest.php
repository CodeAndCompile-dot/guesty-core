<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ServiceFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('service');

        $uniqueRule = $id
            ? "required|unique:services,seo_url,{$id}"
            : 'required|unique:services,seo_url';

        return [
            'seo_url' => $uniqueRule,
            'name' => 'required',
        ];
    }
}
