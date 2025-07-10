<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateNewAdminRequest extends FormRequest
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
            "profile_image" => "nullable|image|mimes:jpeg,png,jpg,gif,svg|max:3072",
            "name" => "required|string",
            "email" => "required|string|email|unique:users,email",
            "phone" => "required|string|unique:users,phone",
            "bio" => "nullable|string",
            "password" => "required|string|confirmed|min:8",
            "status" => "required|string|in:active,inactive",
            "roles" => "array",
            "roles.*" => "exists:roles,name",
        ];
    }
}
