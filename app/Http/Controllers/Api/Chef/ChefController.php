<?php

namespace App\Http\Controllers\Api\Chef;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Chef;
use App\Http\Resources\ChefResource;
use App\Http\Resources\ChefDetailsResource;
use App\Models\User;
use Illuminate\Http\Request;

class ChefController extends Controller
{
    /**
     * Get all open Chefs (verified chefs)
     */
    public function getOpenChefs()
    {
        try {
            $openChefs = Chef::with(['user', 'dishes', 'reviews'])
                ->where('is_verified', true)
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Open Resturants retrieved successfully',
                'data' => ChefResource::collection($openChefs),
                'meta' => [
                    'total' => $openChefs->count(),
                    'showing' => $openChefs->count()
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve open Resturants',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Show Chef with its categories and meals
     */
    public function showChefWithCategoriesAndMeals($id)
    {
        try {
            $chef = Chef::with(['user', 'dishes.category', 'dishes.sizes', 'reviews'])
                ->find($id);

            if (!$chef) {
                return response()->json([
                    'success' => false,
                    'message' => 'Resturant not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Resturant details retrieved successfully',
                'data' => new ChefDetailsResource($chef)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve Resturant details',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function searchChefs(Request $request)
    {
        $query = $request->get('query');

        if (!$query) {
            return ApiResponse::error("Search query is required", 422);
        }

        $chefs = User::search($query)->paginate(10);

        if ($chefs->isEmpty()) {
            return ApiResponse::success([], "No chefs found");
        }

        return ApiResponse::withPagination($chefs, "chefs");
    }

}
