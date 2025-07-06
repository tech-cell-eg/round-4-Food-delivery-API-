<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Favorite;
use App\Models\Dish;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class FavoriteController extends Controller
{
    public function add_favourite($dish_id,$customer_id){
        
    //  $customer_id = Auth::id();

     $exist = DB::table('favorites')
        ->where('customer_id', $customer_id)
        ->where('dish_id', $dish_id)
        ->exists();

     if($exist){
        return response()->json(['message' => 'Already in favorites'], 409);
     }
         DB::table('favorites')->insert([
        'customer_id' => $customer_id,
        'dish_id' => $dish_id
    ]);

    return response()->json(['message' => 'Dish added to favorites']);
    }
}
