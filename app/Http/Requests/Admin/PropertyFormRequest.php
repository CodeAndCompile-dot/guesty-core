<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class PropertyFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $propertyId = $this->route('property') ?? $this->route('id');

        $rules = [
            'name'    => ['required', 'string', 'max:255'],
            'seo_url' => ['required', 'string', 'max:255', 'unique:properties,seo_url'],
        ];

        // On update, exclude the current record from unique check
        if ($propertyId) {
            $rules['seo_url'] = ['required', 'string', 'max:255', "unique:properties,seo_url,{$propertyId}"];
        }

        return $rules;
    }
}
