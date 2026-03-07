<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class BlogFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('blog') ?? $this->route('id');

        $rules = [
            'seo_url'          => ['required', 'string', 'max:255', 'unique:blogs,seo_url'],
            'blog_category_id' => ['required'],
            'title'            => ['required', 'string', 'max:255'],
        ];

        if ($id) {
            $rules['seo_url'] = ['required', 'string', 'max:255', "unique:blogs,seo_url,{$id}"];
        }

        return $rules;
    }
}
