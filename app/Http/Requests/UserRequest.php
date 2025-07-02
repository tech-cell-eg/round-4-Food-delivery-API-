<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'type' => 'required|in:customer,chef',
            'phone' => 'nullable|string|max:20',

             // Optional fields for type-specific creation
            'speciality' => 'nullable|string|max:255',
            'experience_years' => 'nullable|integer|min:0',
            'preferred_payment_method' => 'nullable|string|in:cash_on_delivery,credit_card,paypal',
        ];
    }
}
