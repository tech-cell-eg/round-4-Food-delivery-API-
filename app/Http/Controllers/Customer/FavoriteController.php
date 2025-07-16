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


class FavoriteController extends Controller
{
    public function add_favourite($dish_id)
    {

        $user = User::find(Auth::id());


        $exist = DB::table('favorites')
            ->where('customer_id', $user->id)
            ->where('dish_id', $dish_id)
            ->exists();

        if ($exist) {
            return ApiResponse::error(['message' => 'Already in favorites'], 409);
        }
        DB::table('favorites')->insert([
            'customer_id' => $user->id,
            'dish_id' => $dish_id
        ]);

        return ApiResponse::success([
            'data' => $user->customer->favorites
        ], 'تم تحديث المفضلة بنجاح', 200);
    }

    public function toggleFavorite($dish_id)
    {
        $user = User::find(Auth::id());
        // find favorite by dish_id
        $favorite = Favorite::where('customer_id', $user->id)
            ->where('dish_id', $dish_id)
            ->first();
        if ($favorite) {
            $favorite->delete();
            return ApiResponse::success([
                'data' => $user->customer->favorites
            ], 'تم تحديث المفضلة بنجاح', 200);
        }

        $favorite = Favorite::create([
            'customer_id' => Auth::id(),
            'dish_id' => $dish_id
        ]);

        return ApiResponse::success([
            'data' => $user->customer->favorites
        ], 'تم تحديث المفضلة بنجاح', 200);
    }

    /**
     * Remove favorite
     */
    public function removeFavorite($dish_id)
    {
        $user = User::find(Auth::id());
        // find favorite by dish_id
        $favorite = Favorite::where('customer_id', $user->id)
            ->where('dish_id', $dish_id)
            ->first();
        if ($favorite) {
            $favorite->delete();
            return ApiResponse::success([
                'data' => $user->customer->favorites
            ], 'تم تحديث المفضلة بنجاح', 200);
        }
    }
}
