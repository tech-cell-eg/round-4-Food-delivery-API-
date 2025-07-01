<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index() 
    {
        $categories = Category::all()->select("name", "image", "meal_type");
        return ApiResponse::success($categories, "Categories Retrieved Successfully");
    }
}
