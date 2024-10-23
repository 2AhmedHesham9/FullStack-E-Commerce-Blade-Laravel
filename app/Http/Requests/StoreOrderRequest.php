<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if(Auth::check())
        {
            return true;
        }
        return FALSE;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'=>'required|max:100',
            'phone'=>'required|numeric|digits:11',
            'locality'=>'required',
            'address'=>'required',
            'city'=>'required',
            'state'=>'required',
            // 'country'=>'required',
            'landmark'=>'required',
            'zip'=>'required|numeric|digits:6',
            'mode'=>'required'

        ];
    }
}
