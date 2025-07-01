<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Category;

class CategoryWithMealsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // This resource expects an array with category_id and meals
        $categoryId = $this->resource['category_id'];
        $meals = $this->resource['meals'];
        
        $category = Category::find($categoryId);
        
        return [
            'category' => [
                'id' => $categoryId,
                'name' => $category ? $category->name : 'Uncategorized',
                'image' => $category ? $category->image : null,
                'meal_type' => $category ? $category->meal_type : null,
                'description' => $category ? $category->description : null
            ],
            'meals_count' => count($meals),
            'meals' => DishResource::collection(collect($meals))
        ];
    }
}
