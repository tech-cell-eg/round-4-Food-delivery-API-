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
        // 'type' => 'required|in:customer,chef',
        'phone' => 'nullable|string|max:20',

        //Image must be a valid image file under 2MB
        'profile_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

        //Bio must be a string if provided
        'bio' => 'nullable|string|max:1000',

        //Type-specific optional fields
        'speciality' => 'nullable|string|max:255',
        'experience_years' => 'nullable|integer|min:0',
        'preferred_payment_method' => 'nullable|string|in:cash_on_delivery,credit_card,paypal',
    ];
}
public function messages(): array
{
    return [
        'name.required' => 'The name is required.',
        'email.required' => 'The email is required.',
        'email.email' => 'Please enter a valid email address.',
        'email.unique' => 'This email is already registered.',
        'password.required' => 'The password is required.',
        'password.min' => 'The password must be at least 8 characters.',
        // 'type.required' => 'The user type is required.',
        // 'type.in' => 'The selected type must be customer or chef.',
        'profile_image.image' => 'The profile image must be a valid image.',
        'profile_image.mimes' => 'Allowed image formats: jpg, jpeg, png, webp.',
        'profile_image.max' => 'The image must be smaller than 2MB.',
        'bio.string' => 'The bio must be text.',
        'bio.max' => 'The bio may not be longer than 1000 characters.',
        'preferred_payment_method.in' => 'The payment method must be one of: cash_on_delivery, credit_card, paypal.',
    ];
}

    
}
