<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Helpers\ApiResponse;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\DishResource;
use App\Models\Category;
use App\Models\Dish;


class CategoryController extends Controller
{

    public function index()
    {
        $categories =  Category::all();
        return
            ApiResponse::success(
                CategoryResource::collection($categories)
            );
    }
    public function mealTypes()
    {
        $mealTypes = Category::select('meal_type')
            ->distinct()
            ->orderBy('meal_type')
            ->pluck('meal_type');

        return ApiResponse::success($mealTypes);
    }
    public function getDishesByCategory($categoryId)
    {

        $category = Category::with('dishes.sizes')->find($categoryId);

        if (!$category) {
            return ApiResponse::error("'category not found'");
        }
        return ApiResponse::success([
            'category' => new CategoryResource($category),
            'dishes' => DishResource::collection($category->dishes)
        ]);
    }
    public function getDishesByMealType($mealType)
    {
        $dishes = Dish::with(['category', 'sizes'])
            ->whereHas('category', function ($query) use ($mealType) {
                $query->where('meal_type', $mealType);
            })
            ->get();
        return ApiResponse::success(DishResource::collection($dishes));
    }
}
