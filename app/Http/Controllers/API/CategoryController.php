<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * عرض قائمة الفئات
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::withCount('dishes')->get();

        return response()->json([
            'data' => $categories,
            'message' => 'تم جلب الفئات بنجاح',
        ]);
    }

    /**
     * عرض تفاصيل فئة محددة مع الأطباق المرتبطة
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = Category::with(['dishes' => function ($query) {
            $query->where('is_available', true)
                  ->with('chef', 'sizes');
        }])->findOrFail($id);

        return response()->json([
            'data' => $category,
            'message' => 'تم جلب تفاصيل الفئة بنجاح',
        ]);
    }

    /**
     * إضافة فئة جديدة (للمسؤولين فقط)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // يمكن إضافة التحقق من صلاحيات المستخدم هنا

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:categories',
            'image' => 'nullable|string',
            'meal_type' => 'required|string|in:breakfast,lunch,dinner,snack,dessert',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $category = Category::create([
            'name' => $request->name,
            'image' => $request->image,
            'meal_type' => $request->meal_type,
        ]);

        return response()->json([
            'data' => $category,
            'message' => 'تم إضافة الفئة بنجاح',
        ], 201);
    }

    /**
     * تحديث فئة (للمسؤولين فقط)
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // يمكن إضافة التحقق من صلاحيات المستخدم هنا

        $category = Category::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255|unique:categories,name,' . $id,
            'image' => 'nullable|string',
            'meal_type' => 'sometimes|string|in:breakfast,lunch,dinner,snack,dessert',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $category->update($request->only(['name', 'image', 'meal_type']));

        return response()->json([
            'data' => $category,
            'message' => 'تم تحديث الفئة بنجاح',
        ]);
    }

    /**
     * حذف فئة (للمسؤولين فقط)
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // يمكن إضافة التحقق من صلاحيات المستخدم هنا

        $category = Category::findOrFail($id);
        
        // التحقق من عدم وجود أطباق مرتبطة بالفئة
        if ($category->dishes()->count() > 0) {
            return response()->json([
                'message' => 'لا يمكن حذف الفئة لأنها تحتوي على أطباق مرتبطة بها'
            ], 422);
        }

        $category->delete();

        return response()->json([
            'message' => 'تم حذف الفئة بنجاح',
        ]);
    }
}
