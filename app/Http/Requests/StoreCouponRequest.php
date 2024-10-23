<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCouponRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'code'=>'required|unique:coupons,code',
            'type'=>'required|not_in:0',
            'value'=>'required|numeric',
            'cart_value'=>'required|numeric',
            'expiry_date'=>'required|date'
        ];
    }
}
