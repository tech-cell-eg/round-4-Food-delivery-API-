<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Favorite;
use App\Models\Dish;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Helpers\ApiResponse;
use Illuminate\Validation\ValidationException;

class FavoriteController extends Controller
{
    public function index(Request $request)
    {
        $customer = $request->user()->customer;

        $favoriteDishes = $customer->favorite_dishs()->with('dish')->get();

        if ($favoriteDishes->isEmpty()) {
            return ApiResponse::success([], 'Has No Favorite Meals');
        }

        return ApiResponse::success($favoriteDishes);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'dish_id' => 'required|exists:dishes,id',
            ]);
        } catch (ValidationException $e) {
            return ApiResponse::error($e->errors(), 422);
        }

        $customer = $request->user()->customer;

        $alreadyExists = Favorite::where('customer_id', $customer->id)
            ->where('dish_id', $validated['dish_id'])
            ->exists();

        if ($alreadyExists) {
            return ApiResponse::error('This dish is already in your favorites.', 409); // 409: Conflict
        }

        Favorite::create([
            'customer_id' => $customer->id,
            'dish_id'     => $validated['dish_id'],
        ]);

        return ApiResponse::success([], 'Dish added to favorites successfully.');
    }


    public function destroy(Request $request, $dishId)
    {
        $customer = $request->user()->customer;

        $favorite = Favorite::where('customer_id', $customer->id)
            ->where('dish_id', $dishId)
            ->first();

        if (!$favorite) {
            return ApiResponse::error('This dish is not in your favorites.', 404);
        }

        $favorite->delete();

        return ApiResponse::success([], 'Dish removed from favorites successfully.');
    }
}
