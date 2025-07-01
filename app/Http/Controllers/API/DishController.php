<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Dish;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DishController extends Controller
{
    /**
     * عرض قائمة الأطباق
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Dish::with(['chef', 'category', 'sizes', 'ingredients']);

        // تصفية حسب الفئة
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // البحث في اسم الطبق أو الوصف
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // الترتيب
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'price_asc':
                    $query->orderBy('base_price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('base_price', 'desc');
                    break;
                case 'rating':
                    $query->orderBy('avg_rating', 'desc');
                    break;
                default:
                    $query->latest();
                    break;
            }
        } else {
            $query->latest();
        }

        $dishes = $query->paginate(10);

        return response()->json([
            'data' => $dishes,
            'message' => 'تم جلب الأطباق بنجاح',
        ]);
    }

    /**
     * عرض تفاصيل طبق محدد
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $dish = Dish::with(['chef', 'category', 'sizes', 'ingredients', 'reviews.customer'])
            ->findOrFail($id);

        return response()->json([
            'data' => $dish,
            'message' => 'تم جلب تفاصيل الطبق بنجاح',
        ]);
    }

    /**
     * إضافة طبق جديد (للطهاة فقط)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // التحقق من أن المستخدم طاهي
        if ($request->user()->type !== 'chef') {
            return response()->json([
                'message' => 'غير مصرح لك بإضافة أطباق'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'base_price' => 'required|numeric|min:0',
            'preparation_time' => 'required|integer|min:1',
            'image' => 'nullable|string',
            'is_available' => 'boolean',
            'sizes' => 'nullable|array',
            'ingredients' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // الحصول على معرف الطاهي المرتبط بالمستخدم الحالي
        $chefId = $request->user()->chef->id;

        $dish = Dish::create([
            'name' => $request->name,
            'description' => $request->description,
            'chef_id' => $chefId,
            'category_id' => $request->category_id,
            'base_price' => $request->base_price,
            'preparation_time' => $request->preparation_time,
            'image' => $request->image,
            'is_available' => $request->is_available ?? true,
        ]);

        // إضافة أحجام الطبق إذا وجدت
        if ($request->has('sizes') && is_array($request->sizes)) {
            foreach ($request->sizes as $size) {
                $dish->sizes()->create([
                    'name' => $size['name'],
                    'price_multiplier' => $size['price_multiplier'],
                ]);
            }
        }

        // إضافة مكونات الطبق إذا وجدت
        if ($request->has('ingredients') && is_array($request->ingredients)) {
            $dish->ingredients()->attach($request->ingredients);
        }

        return response()->json([
            'data' => $dish->load(['sizes', 'ingredients']),
            'message' => 'تم إضافة الطبق بنجاح',
        ], 201);
    }

    /**
     * تحديث طبق (للطهاة فقط)
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // التحقق من أن المستخدم طاهي
        if ($request->user()->type !== 'chef') {
            return response()->json([
                'message' => 'غير مصرح لك بتحديث الأطباق'
            ], 403);
        }

        $dish = Dish::findOrFail($id);

        // التحقق من أن الطبق ينتمي للطاهي الحالي
        if ($dish->chef_id !== $request->user()->chef->id) {
            return response()->json([
                'message' => 'غير مصرح لك بتحديث هذا الطبق'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'category_id' => 'sometimes|exists:categories,id',
            'base_price' => 'sometimes|numeric|min:0',
            'preparation_time' => 'sometimes|integer|min:1',
            'image' => 'nullable|string',
            'is_available' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $dish->update($request->only([
            'name',
            'description',
            'category_id',
            'base_price',
            'preparation_time',
            'image',
            'is_available'
        ]));

        return response()->json([
            'data' => $dish->fresh(['chef', 'category', 'sizes', 'ingredients']),
            'message' => 'تم تحديث الطبق بنجاح',
        ]);
    }

    /**
     * حذف طبق (للطهاة فقط)
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        // التحقق من أن المستخدم طاهي
        if ($request->user()->type !== 'chef') {
            return response()->json([
                'message' => 'غير مصرح لك بحذف الأطباق'
            ], 403);
        }

        $dish = Dish::findOrFail($id);

        // التحقق من أن الطبق ينتمي للطاهي الحالي
        if ($dish->chef_id !== $request->user()->chef->id) {
            return response()->json([
                'message' => 'غير مصرح لك بحذف هذا الطبق'
            ], 403);
        }

        // حذف الطبق
        $dish->delete();

        return response()->json([
            'message' => 'تم حذف الطبق بنجاح',
        ]);
    }
}
