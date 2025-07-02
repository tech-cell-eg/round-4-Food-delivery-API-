<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CartRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            // Add more rules as needed
        ];
    }

    /**
     * Custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'product_id.required' => __('Product is required.'),
            'product_id.exists' => __('Selected product does not exist.'),
            'quantity.required' => __('Quantity is required.'),
            'quantity.integer' => __('Quantity must be a number.'),
            'quantity.min' => __('Quantity must be at least 1.'),
        ];
    }
}
