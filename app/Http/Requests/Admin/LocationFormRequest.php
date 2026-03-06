<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class LocationFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $locationId = $this->route('location') ?? $this->route('id');

        $rules = [
            'name'    => ['required', 'string', 'max:255'],
            'seo_url' => ['required', 'string', 'max:255', 'unique:locations,seo_url'],
        ];

        if ($locationId) {
            $rules['seo_url'] = ['required', 'string', 'max:255', "unique:locations,seo_url,{$locationId}"];
        }

        return $rules;
    }
}
