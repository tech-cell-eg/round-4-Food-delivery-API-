<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAdminRequest extends FormRequest
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
        $adminId = $this->route('admin');

        return [
            "profile_image" => "nullable|image|mimes:jpeg,png,jpg,gif,svg|max:3072",
            "name" => "required|string",
            "email" => "required|email|unique:users,email," . $adminId,
            "phone" => "required|string|unique:users,phone," . $adminId,
            "bio" => "nullable|string",
            "password" => "nullable|string|confirmed|min:8",
            "status" => "required|string|in:active,inactive",
            "roles" => "array",
            "roles.*" => "exists:roles,name",
        ];
    }
}
