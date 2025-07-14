<?php

namespace App\Http\Controllers\API\Chef;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Ingredient;
use Illuminate\Http\Request;

class IngredientsController extends Controller
{
    public function index()
    {
        $ingredients = Ingredient::all();

        return ApiResponse::success($ingredients);
    }
}
