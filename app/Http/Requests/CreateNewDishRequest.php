<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateNewDishRequest extends FormRequest
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
            'chef_id' => 'required|exists:chefs,id',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string|max:1000',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_available' => 'required|boolean',
            'ingredients' => 'nullable|array',
            'ingredients.*' => 'exists:ingredients,id',
            'sizes' => 'required|array|min:1',
            'sizes.*.size' => 'required|in:small,medium,large',
            'sizes.*.price' => 'required|numeric|min:0.01',
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Dish name is required.',
            'name.max' => 'Dish name cannot exceed 255 characters.',
            'chef_id.required' => 'Please select a chef.',
            'chef_id.exists' => 'Selected chef does not exist.',
            'category_id.exists' => 'Selected category does not exist.',
            'description.max' => 'Description cannot exceed 1000 characters.',
            'image.image' => 'The file must be an image.',
            'image.mimes' => 'Image must be a file of type: jpeg, png, jpg, gif.',
            'image.max' => 'Image size cannot exceed 2MB.',
            'is_available.required' => 'Please select availability status.',
            'ingredients.*.exists' => 'One or more selected ingredients do not exist.',
            'sizes.required' => 'At least one size is required.',
            'sizes.min' => 'At least one size must be added.',
            'sizes.*.size.required' => 'Size selection is required.',
            'sizes.*.size.in' => 'Size must be small, medium, or large.',
            'sizes.*.price.required' => 'Price is required for each size.',
            'sizes.*.price.numeric' => 'Price must be a valid number.',
            'sizes.*.price.min' => 'Price must be greater than 0.',
        ];
    }

    /**
     * Custom validation rules.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $sizes = $this->input('sizes', []);
            $selectedSizes = array_column($sizes, 'size');

            // Check for duplicate sizes
            if (count($selectedSizes) !== count(array_unique($selectedSizes))) {
                $validator->errors()->add('sizes', 'Duplicate sizes are not allowed. Each size can only be selected once.');
            }
        });
    }
}
