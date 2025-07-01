<?php

namespace App\Http\Requests;

use App\Helpers\ApiResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreNewDish extends FormRequest
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
            'description' => 'required|string|max:1000',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:3072',
            'category_id' => 'required|exists:categories,id',
            'is_available' => 'required|boolean',
            'ingredients' => 'required|array|min:1',
            'ingredients.*' => 'required|integer|exists:ingredients,id',
            'sizes' => 'required|array|min:1',
            'sizes.*.size' => 'required|string|in:small,medium,large',
            'sizes.*.price' => 'required|numeric|min:0.01',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The dish name is required.',
            'name.string' => 'The dish name must be a string.',
            'name.max' => 'The dish name may not be greater than 255 characters.',

            'description.string' => 'The description must be a string.',
            'description.max' => 'The description may not be greater than 1000 characters.',

            'image.required' => 'The image is required.',
            'image.image' => 'The file must be an image.',
            'image.mimes' => 'The image must be a file of type: jpg, jpeg, png, webp.',
            'image.max' => 'The image must not be larger than 2MB.',

            'category_id.exists' => 'The selected category does not exist.',

            'is_available.boolean' => 'The availability must be true or false.',

            'ingredients.required' => 'At least one ingredient is required.',
            'ingredients.array' => 'The ingredients must be an array.',
            'ingredients.min' => 'You must select at least one ingredient.',
            'ingredients.*.required' => 'Each ingredient is required.',
            'ingredients.*.integer' => 'Each ingredient must be an integer.',
            'ingredients.*.exists' => 'One or more selected ingredients do not exist.',

            'sizes.required' => 'At least one size is required.',
            'sizes.array' => 'The sizes must be an array.',
            'sizes.min' => 'You must select at least one size.',
            'sizes.*.required' => 'Each size is required.',
            'sizes.*.string' => 'Each size must be a string.',
            'sizes.*.size.in' => 'Size must be one of: small, medium, or large.',
            'sizes.*.max' => 'The size may not be greater than 50 characters.',
            'sizes.*.price.required' => 'Each price is required.',
            'sizes.*.price.numeric' => 'Each price must be a number.',
            'sizes.*.price.min' => 'The price must be at least 0.01.',
        ];
    }


    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();

        throw new HttpResponseException(
            ApiResponse::validationError($errors)
        );
    }
}
