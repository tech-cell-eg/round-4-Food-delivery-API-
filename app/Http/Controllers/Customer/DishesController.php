<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dish;
use Illuminate\Support\Facades\DB;
// use App\Http\Resources\DishResource;

class DishesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    
$dishes = DB::table('dishes')
   
    ->join('dish_sizes', 'dishes.id', '=','dish_sizes.dish_id')
    ->join('chefs', 'dishes.chef_id', '=','chefs.id')
    ->join('users','chefs.id','=','users.id')
    ->select('dishes.name as dish_name','dishes.image as dish_image','dish_sizes.price as dish_price','users.name as chef_name')
    ->get();

       return $dishes;

    }


 

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
    $dish =DB::table('dishes')
    ->join('chefs', 'dishes.chef_id', '=', 'chefs.id')
    ->join('categories', 'dishes.category_id', '=', 'categories.id')
    ->join('dish_ingredients', 'dishes.id', '=', 'dish_ingredients.dish_id')
    ->join('ingredients', 'dish_ingredients.ingredient_id', '=', 'ingredients.id')
    ->where('dishes.id', $id)
    ->select('dishes.name as dish_name','dishes.image as dish_image','dishes.total_rate as dish_total_rate','dishes.avg_rate as dish_avg_rate',
    'chefs.location as chef_location','dishes.description as dish_description',
    'categories.name as category_name', 'ingredients.name as ingredient_name')
    ->first();

       return $dish;
    }

    public function search( Request $request){


        $search =$request->input('search') ;
       
   $dishes = DB::table('dishes')
    ->join('dish_sizes', 'dishes.id', '=','dish_sizes.dish_id')
    ->join('chefs', 'dishes.chef_id', '=','chefs.id')
    ->join('users','chefs.id','=','users.id')
    ->where( function($query) use ($search){
        $query->where('dishes.name','like',"%$search%")
        ->OrWhere('users.name','like',"%$search%");
    })
    ->select('dishes.name as dish_name','dishes.image as dish_image','dish_sizes.price as dish_price','users.name as chef_name')->get();



    

       return $dishes;

    }

        public function filter( Request $request){

               $rate = $request->query('rate');
               $price = $request->query('price');

    $query =DB::table('dishes')
    ->join('dish_sizes', 'dishes.id', '=','dish_sizes.dish_id')
    ->join('chefs', 'dishes.chef_id', '=','chefs.id')
    ->join('users','chefs.id','=','users.id')
    ->select('dishes.name as dish_name','dishes.image as dish_image','dish_sizes.price as dish_price','users.name as chef_name','dishes.total_rate as dish_total_rate');
    
    if($rate !== null){
        $query->where('dishes.avg_rate', '>=', $rate);
    }

   
    if($price !== null){
        $query->where('dish_sizes.price', '<=', $price);
    }
    

    $dishes = $query->get();
       return $dishes;

    }


}
