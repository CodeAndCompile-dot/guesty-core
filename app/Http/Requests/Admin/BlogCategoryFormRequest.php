<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class BlogCategoryFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('blog_category') ?? $this->route('id');

        $rules = [
            'seo_url'   => ['required', 'string', 'max:255', 'unique:blog_categories,seo_url'],
            'title'     => ['required', 'string', 'max:255'],
            'ordering'  => ['required'],
        ];

        if ($id) {
            $rules['seo_url'] = ['required', 'string', 'max:255', "unique:blog_categories,seo_url,{$id}"];
        }

        return $rules;
    }
}
