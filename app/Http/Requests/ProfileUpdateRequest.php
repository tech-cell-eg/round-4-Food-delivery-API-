<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
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
        $admin = Auth::guard('admin')->user();

        return [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($admin->id)
            ],
            'phone' => [
                "nullable",
                "string",
                "max:20",
                Rule::unique('users', 'phone')->ignore($admin->id),
            ],
            'bio' => 'nullable|string|max:500',
            "profile_image" => "nullable|image|mimes:jpeg,png,jpg,gif,svg|max:3072",
            'current_password' => 'nullable|string',
            'password' => 'nullable|string|min:8|confirmed',
        ];
    }
}
