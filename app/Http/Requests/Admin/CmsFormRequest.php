<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CmsFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('cm') ?? $this->route('cms');

        $uniqueRule = $id
            ? "required|unique:cms,seo_url,{$id}"
            : 'required|unique:cms,seo_url';

        return [
            'seo_url' => $uniqueRule,
            'name' => 'required',
        ];
    }
}
