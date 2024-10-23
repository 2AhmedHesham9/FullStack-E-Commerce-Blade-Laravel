<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
            'slug' => 'required|unique:products,slug,' . $this->request->get('id'),
            'short_description' => 'required',
            'description' => 'required',
            'regular_price' => 'required|numeric',
            'sale_price' => 'numeric|nullable',
            'SKU' => 'required',
            'stock_status' => 'required',
            'featured' => 'required',
            // 'quantity' => 'required|numeric',
            'image' => 'mimes:png,jpg,jpeg,webp|max:2048',
            'category_id' => 'required|not_in:0',
            'brand_id' => 'required|not_in:0',
            'sizes' => 'required|array',
            'sizes.*' => 'string', // Assuming sizes are strings (e.g., 'S', 'M', etc.)
            'quantities' => 'required|array',
            'quantities.*' => 'integer|min:1',
        ];
    }
}
