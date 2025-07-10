<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDishRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'chef_id' => ['required', 'exists:chefs,id'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'description' => ['nullable', 'string', 'max:1000'],
            'image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,gif', 'max:2048'],
            'is_available' => ['required', 'boolean'],
            'sizes' => ['required', 'array', 'min:1'],
            'sizes.*.size' => ['required', 'in:small,medium,large'],
            'sizes.*.price' => ['required', 'numeric', 'min:0.01'],
            'ingredients' => ['nullable', 'array'],
            'ingredients.*' => ['exists:ingredients,id'],
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $sizes = $this->input('sizes', []);
            $sizeValues = array_column($sizes, 'size');
            
            if (count($sizeValues) !== count(array_unique($sizeValues))) {
                $validator->errors()->add('sizes', 'Each dish size must be unique. Duplicate sizes are not allowed.');
            }
        });
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'The dish name is required.',
            'name.max' => 'The dish name must not exceed 255 characters.',
            'chef_id.required' => 'Please select a chef for this dish.',
            'chef_id.exists' => 'The selected chef is invalid.',
            'category_id.exists' => 'The selected category is invalid.',
            'description.max' => 'The description must not exceed 1000 characters.',
            'image.image' => 'The uploaded file must be an image.',
            'image.mimes' => 'The image must be a file of type: jpeg, jpg, png, gif.',
            'image.max' => 'The image size must not exceed 2MB.',
            'is_available.required' => 'Please select availability status.',
            'is_available.boolean' => 'The availability status must be yes or no.',
            'sizes.required' => 'At least one size is required for this dish.',
            'sizes.min' => 'At least one size is required for this dish.',
            'sizes.*.size.required' => 'Please select a size.',
            'sizes.*.size.in' => 'The size must be small, medium, or large.',
            'sizes.*.price.required' => 'Please enter a price for this size.',
            'sizes.*.price.numeric' => 'The price must be a valid number.',
            'sizes.*.price.min' => 'The price must be at least $0.01.',
            'ingredients.*.exists' => 'One or more selected ingredients are invalid.',
        ];
    }
}
