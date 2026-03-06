<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class OurTeamFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('our_team');

        $uniqueRule = $id
            ? "required|unique:our_teams,seo_url,{$id}"
            : 'required|unique:our_teams,seo_url';

        return [
            'seo_url' => $uniqueRule,
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required',
            'profile' => 'required',
        ];
    }
}
