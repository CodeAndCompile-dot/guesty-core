<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AttractionCategoryFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('attraction_category') ?? $this->route('id');

        $rules = [
            'seo_url' => ['required', 'string', 'max:255', 'unique:attraction_categories,seo_url'],
            'name'    => ['required', 'string', 'max:255'],
        ];

        if ($id) {
            $rules['seo_url'] = ['required', 'string', 'max:255', "unique:attraction_categories,seo_url,{$id}"];
        }

        return $rules;
    }
}
