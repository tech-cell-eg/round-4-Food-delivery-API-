<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Meal;
use App\Models\Category;
use App\Models\Restaurant;
use App\Models\Country;
use Illuminate\Http\Request;

class MealController extends Controller
{
    public function index(Request $request)
    {
        $query = Meal::query()->with('restaurant', 'category');
        
        // تطبيق الفلاتر
        if ($request->has('country_id') && $request->country_id) {
            $query->whereHas('restaurant', function($q) use ($request) {
                $q->where('country_id', $request->country_id);
            });
        }
        
        if ($request->has('category_id') && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }
        
        if ($request->has('restaurant_id') && $request->restaurant_id) {
            $query->where('restaurant_id', $request->restaurant_id);
        }
        
        if ($request->has('min_price') && $request->min_price) {
            $query->where('price', '>=', $request->min_price);
        }
        
        if ($request->has('max_price') && $request->max_price) {
            $query->where('price', '<=', $request->max_price);
        }
        
        $meals = $query->paginate(12);
        $categories = Category::all();
        $restaurants = Restaurant::all();
        $countries = Country::all();
        
        return view('meals.index', compact('meals', 'categories', 'restaurants', 'countries'));
    }
}
