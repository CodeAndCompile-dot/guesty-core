<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class GuestyPropertyFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('guesty_property');

        return [
            'seo_url' => 'required|unique:guesty_properties,seo_url,' . $id,
        ];
    }
}
