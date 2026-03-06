<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class LandingCmsFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('landing_cm');

        $uniqueRule = $id
            ? "required|unique:landing_cms,seo_url,{$id}"
            : 'required|unique:landing_cms,seo_url';

        return [
            'seo_url' => $uniqueRule,
            'name' => 'required',
        ];
    }
}
