<?php

namespace App\Http\Controllers;

use App\ApiResponses;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\DishResource;
use App\Models\Category;
use App\Models\Dish;

use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use ApiResponses;
    public function index()
    {
        $categories =  Category::all();
        return $this->successResponse(
            CategoryResource::collection($categories),
            'Categories retrieved successfully'
        );
    }
    public function mealTypes()
    {
        $mealTypes = Category::select('meal_type')
            ->distinct()
            ->orderBy('meal_type')
            ->pluck('meal_type');

        return $this->successResponse($mealTypes);
    }
    public function getDishesByCategory($categoryId)
    {

        $category = Category::with('dishes.sizes')->find($categoryId);

        if (!$category) {
            return $this->errorResponse('category not found');
        }

        return $this->successResponse([
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

        return $this->successResponse(DishResource::collection($dishes), "dishes retrived successfully");
    }
}
