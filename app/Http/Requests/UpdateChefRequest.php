<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateChefRequest extends FormRequest
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
        $chefId = $this->route('chef');
        
        return [
            // User Information
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($chefId)],
            'password' => 'nullable|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:1000',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'address' => 'nullable|string|max:500',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            
            // Chef Specific Information
            'national_id' => ['required', 'string', 'max:20', Rule::unique('chefs', 'national_id')->ignore($chefId)],
            'description' => 'nullable|string|max:1000',
            'location' => 'nullable|string|max:500',
            'balance' => 'nullable|numeric|min:0|max:99999.99',
            'is_verified' => 'nullable|boolean',
            'email_verified' => 'nullable|boolean',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Chef name is required',
            'email.required' => 'Email address is required',
            'email.email' => 'Please enter a valid email address',
            'email.unique' => 'This email address is already registered',
            'password.min' => 'Password must be at least 8 characters long',
            'password.confirmed' => 'Password confirmation does not match',
            'national_id.required' => 'National ID is required',
            'national_id.unique' => 'This National ID is already registered',
            'profile_image.image' => 'Profile image must be an image file',
            'profile_image.mimes' => 'Profile image must be a jpeg, png, jpg, or gif file',
            'profile_image.max' => 'Profile image must not exceed 2MB',
            'balance.numeric' => 'Balance must be a valid number',
            'balance.min' => 'Balance cannot be negative',
            'balance.max' => 'Balance cannot exceed 99,999.99',
            'latitude.between' => 'Latitude must be between -90 and 90',
            'longitude.between' => 'Longitude must be between -180 and 180',
        ];
    }
}
