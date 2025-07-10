<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
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
        $categoryId = $this->route("category");

        return [
            'name' => 'required|string|max:255|unique:categories,name,' . $categoryId,
            'meal_type' => 'required|string|in:breakfast,lunch,dinner',
            'image' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
        ];
    }
}
