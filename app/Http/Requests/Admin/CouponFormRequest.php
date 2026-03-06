<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CouponFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $couponId = $this->route('coupon') ?? $this->route('id');

        $rules = [
            'code'        => ['required', 'string', 'max:255', 'unique:coupons,code'],
            'type'        => ['required', 'string'],
            'property_id' => ['required'],
        ];

        if ($couponId) {
            $rules['code'] = ['required', 'string', 'max:255', "unique:coupons,code,{$couponId}"];
        }

        return $rules;
    }
}
