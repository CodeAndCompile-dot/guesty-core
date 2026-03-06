<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SeoCmsFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('seo_page');

        $uniqueRule = $id
            ? "required|unique:seo_pages,seo_url,{$id}"
            : 'required|unique:seo_pages,seo_url';

        return [
            'seo_url' => $uniqueRule,
            'name' => 'required',
        ];
    }
}
