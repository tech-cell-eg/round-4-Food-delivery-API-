<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerRequest extends FormRequest
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
        $customerId = $this->route('customer');

        return [
            "profile_image" => "nullable|image|mimes:jpeg,png,jpg,gif,svg|max:3072",
            "name" => "required|string",
            "email" => "required|string|email|unique:users,email," . $customerId,
            "phone" => "required|string|unique:users,phone," . $customerId,
            "bio" => "nullable|string",
            "password" => "nullable|string|confirmed|min:8",
            "status" => "required|string|in:active,inactive",
            "email_verified" => "nullable|boolean",
        ];
    }
}
