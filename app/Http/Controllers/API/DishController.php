<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Dish;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Helpers\ApiResponse;

class DishController extends Controller
{
    /**
     * عرض قائمة الأطباق مع إمكانية التصفية والبحث
     */
    public function index(Request $request)
    {
        $query = Dish::with(['chef', 'category', 'sizes', 'ingredients']);

        // البحث بالاسم
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        // التصفية حسب الفئة
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // التصفية حسب الشيف
        if ($request->has('chef_id')) {
            $query->where('chef_id', $request->chef_id);
        }

        // التصفية حسب التوفر
        if ($request->has('is_available')) {
            $query->where('is_available', $request->is_available);
        }

        // الترتيب حسب التقييم
        if ($request->has('sort_by_rating') && $request->sort_by_rating) {
            $query->orderBy('avg_rate', 'desc');
        }

        // الترتيب حسب السعر (من خلال أصغر حجم)
        if ($request->has('sort_by_price')) {
            if ($request->sort_by_price === 'asc') {
                $query->whereHas('sizes', function ($q) {
                    $q->orderBy('price', 'asc');
                });
            } elseif ($request->sort_by_price === 'desc') {
                $query->whereHas('sizes', function ($q) {
                    $q->orderBy('price', 'desc');
                });
            }
        }

        // التقسيم إلى صفحات
        $perPage = $request->per_page ?? 10;
        $dishes = $query->paginate($perPage);

        return response()->json([
            'status' => 'success',
            'data' => $dishes
        ]);
    }

    /**
     * عرض تفاصيل طبق محدد
     */
    public function show($id)
    {
        $dish = Dish::with(['chef', 'category', 'sizes', 'ingredients', 'reviews.customer.user'])
            ->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $dish
        ]);
    }

    /**
     * عرض قائمة الفئات
     */
    public function categories()
    {
        $categories = Category::all();

        return response()->json([
            'status' => 'success',
            'data' => $categories
        ]);
    }
}
