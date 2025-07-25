<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dish;
use Illuminate\Support\Facades\DB;
// use App\Http\Resources\DishResource;
use App\Helpers\ApiResponse;
use App\Models\Chef;

class DishesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $dishes = DB::table('dishes')
            ->join('dish_sizes', 'dishes.id', '=', 'dish_sizes.dish_id')
            ->join('chefs', 'dishes.chef_id', '=', 'chefs.id')
            ->join('users', 'chefs.id', '=', 'users.id')
            ->select('dishes.name as dish_name', 'dishes.image as dish_image', 'dish_sizes.price as dish_price', 'users.name as chef_name')
            ->get();

        return $dishes;
    }


    public function show(int $id)
    {
        $dish = Dish::findOr($id, function () {
            return ApiResponse::notFound();
        });

        $dish->load(["chef.user", "category", "sizes", "ingredients"]);

        $data = [
            "dish_id" => $dish->id,
            "dish_name" => $dish->name,
            "dish_image" => $dish->image,
            "dish_description" => $dish->description,
            "dish_avg_rate" => $dish->avg_rate,
            "sizes" => $dish->sizes,
            "ingredients" => $dish->ingredients,
            "category" => [
                "id" => $dish->category->id,
                "name" => $dish->category->name,
            ],
            "chef" => [
                "id" => $dish->chef->user->id,
                "name" => $dish->chef->user->name,
                "bio" => $dish->chef->user->bio,
                "phone" => $dish->chef->user->phone,
                "email" => $dish->chef->user->email,
                "profile_image" => $dish->chef->user->profile_image,
            ],

        ];

        return ApiResponse::success($data);
    }

    public function suggestedResturants()
    {
        // اقتراح المطاعم (الشيفات) الموثقين الأعلى تقييماً والأكثر أطباقاً
        $chefs = Chef::withCount('dishes')
            ->with(['user', 'reviews']) // تأكد من تحميل reviews لتقليل الاستعلامات
            ->where('is_verified', true)
            ->get()
            ->sortByDesc(function ($chef) {
                $avg = $chef->reviews->avg('rating') ?? 0;
                return $avg + ($chef->dishes_count * 0.1);
            })
            ->take(3) // أخذ أفضل 3 فقط
            ->values();

        $data = $chefs->map(function ($chef) {
            return [
                'id' => $chef->id,
                'name' => $chef->user->name ?? '',
                'profile_image' => $chef->user->profile_image ?? '',
                'bio' => $chef->user->bio ?? '',
                'dishes_count' => $chef->dishes_count,
                'avg_rating' => round($chef->reviews->avg('rating') ?? 0, 1),
            ];
        });

        return ApiResponse::success($data, 'Suggested Resturants');
    }

    public function recentKeyword()
    {
        $keywords = [
            'pizza',
            'burger',
            'sushi',
            'falafel',
            'koshary'
        ];
        return ApiResponse::success($keywords, 'Recent keywords (mock)');
    }

    public function popualarMeals()
    {
        $popularDishes = Dish::withCount('orderItems')
            ->with(['chef.user', 'category', 'sizes', 'ingredients', 'reviews'])
            ->orderByDesc('order_items_count')
            ->take(3) // نأخذ فقط أول 3 أطباق
            ->get();

        $data = $popularDishes->map(function ($dish) {
            return [
                "dish_id" => $dish->id,
                "dish_name" => $dish->name,
                "dish_image" => $dish->image,
                "dish_description" => $dish->description,
                "dish_avg_rate" => round($dish->reviews->avg('rating') ?? 0, 1),
                "sizes" => $dish->sizes,
                "ingredients" => $dish->ingredients,
                "category" => [
                    "id" => $dish->category->id ?? null,
                    "name" => $dish->category->name ?? '',
                ],
                "chef" => [
                    "id" => $dish->chef->user->id ?? null,
                    "name" => $dish->chef->user->name ?? '',
                    "bio" => $dish->chef->user->bio ?? '',
                    "phone" => $dish->chef->user->phone ?? '',
                    "email" => $dish->chef->user->email ?? '',
                    "profile_image" => $dish->chef->user->profile_image ?? '',
                ],
            ];
        });

        return ApiResponse::success($data, 'Top 3 Popular Meals');
    }


    public function search(Request $request)
    {

        $search = $request->input('search');

        $dishes = DB::table('dishes')
            ->join('dish_sizes', 'dishes.id', '=', 'dish_sizes.dish_id')
            ->join('chefs', 'dishes.chef_id', '=', 'chefs.id')
            ->join('categories', "dishes.category_id", "=", "categories.id")
            ->join('users', 'chefs.id', '=', 'users.id')
            ->where(function ($query) use ($search) {
                $query->where('dishes.name', 'like', "%$search%")
                    ->OrWhere('users.name', 'like', "%$search%");
            })
            ->select(
                'dishes.id as dish_id',
                'dishes.name as dish_name',
                'dishes.image as dish_image',
                'dish_sizes.size as size',
                'dish_sizes.price as dish_price',
                'categories.name as category_name',
            )->get();

        return $dishes;
    }
}
