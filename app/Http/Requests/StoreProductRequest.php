<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
            'name' => 'required',
            'slug' => 'required|unique:products,slug',
            'short_description' => 'required',
            'description' => 'required',
            'regular_price' => 'required|numeric',
            // 'sale_price' => 'numeric',
            'SKU' => 'required',
            'stock_status' => 'required',
            'featured' => 'required',
            // 'quantity' => 'required|numeric',
            'image' => 'required|mimes:png,jpg,jpeg,webp|max:2048',
            'category_id' => 'required|not_in:0',
            'brand_id' => 'required|not_in:0',
            'sizes' => 'required|array',
            'sizes.*' => 'string',
            'quantities' => 'required|array',
            'quantities.*' => 'integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'category_id' => 'Category can not be empty!',
            'brand_id' => 'Brand can not be empty!',
        ];
    }
}
