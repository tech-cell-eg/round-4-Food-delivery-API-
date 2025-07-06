<?php

namespace App\Http\Controllers\API;

use App\Models\Chef;
use App\Models\Dish;
use App\Models\User;
use App\Models\Review;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Notifications\CustomerActionNotification;

class ReviewController extends Controller
{

    /**
     * عرض قائمة المراجعات لطبق معين
     *
     * @param  int  $dishId
     * @return \Illuminate\Http\Response
     */
    public function dishReviews($dishId)
    {
        $dish = Dish::findOrFail($dishId);

        $reviews = Review::where('dish_id', $dishId)
            ->with(['customer:id,name,profile_image', 'chef:id,name'])
            ->latest('created_at')
            ->paginate(10);

        // حساب متوسط التقييم
        $averageRating = Review::where('dish_id', $dishId)->avg('rating');

        return response()->json([
            'data' => [
                'reviews' => $reviews,
                'average_rating' => round($averageRating, 1),
                'total_reviews' => $reviews->total(),
            ],
            'message' => 'تم جلب المراجعات بنجاح',
        ]);
    }
    /**
     * عرض قائمة المراجعات لطبق معين
     *
     * @param  int  $dishId
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $reviews = Review::where([])
            ->with(['customer:id,name,profile_image', 'chef:id,name'])
            ->latest('created_at')
            ->get();

        return response()->json([
            'data' => [
                'reviews' => $reviews,
                'total_reviews' => $reviews->count(),
            ],
            'message' => 'تم جلب المراجعات بنجاح',
        ]);
    }

    /**
     * إضافة مراجعة جديدة لطبق
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'dish_id' => 'required|exists:dishes,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // التحقق من وجود الطبق
        $dish = Dish::findOrFail($request->dish_id);

        // الحصول على معرف الطاهي من الطبق
        $chefId = $dish->chef_id;

        // التحقق من أن المستخدم لم يقم بمراجعة هذا الطبق من قبل
        $existingReview = Review::where('dish_id', $request->dish_id)
            ->where('customer_id', $request->user()->id)
            ->first();

        if ($existingReview) {
            return response()->json([
                'message' => 'لقد قمت بمراجعة هذا الطبق من قبل. يمكنك تحديث المراجعة الحالية بدلاً من إضافة مراجعة جديدة.'
            ], 422);
        }

        // إنشاء المراجعة
        $review = Review::create([
            'customer_id' => rand(1, 10),
            'chef_id' => $request->chef_id,
            'dish_id' => $request->dish_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'created_at' => now(),
        ]);
        // Notify chef if review is for them
    if ($request->reviewable_type === 'chef') {
        $chef=Chef::findOrFail($chefId);
        if ($chef) {
            $chef->notify(new CustomerActionNotification([
                'title' => 'New Review',
                'message' => "{$request->user()->name} left a new review on your profile.",
                'image' => $user->profile_photo_url ?? null . urlencode($request->user()->name),
                'time' => now()->diffForHumans(),
            ]));
        }
    }
        return response()->json([
            'data' => $review->load(['customer:id,name,profile_image']),
            'message' => 'تم إضافة المراجعة بنجاح',
        ], 201);
    }

    /**
     * تحديث مراجعة موجودة
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // التحقق من وجود المراجعة وأنها تنتمي للمستخدم الحالي
        $review = Review::where('id', $id)
            ->where('customer_id', $request->user()->id)
            ->firstOrFail();

        // تحديث المراجعة
        $review->update([
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return response()->json([
            'data' => $review->load(['customer:id,name,profile_image']),
            'message' => 'تم تحديث المراجعة بنجاح',
        ]);
    }

    /**
     * عرض مراجعة معينة
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $review = Review::findOrFail($id);

        return response()->json([
            'data' => $review->load(['customer:id,name,profile_image', 'chef:id,name']),
            'message' => 'تم جلب المراجعة بنجاح',
        ]);
    }

    /**
     * حذف مراجعة
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        // التحقق من وجود المراجعة وأنها تنتمي للمستخدم الحالي
        $review = Review::where('id', $id)
            ->where('customer_id', $request->user()->id)
            ->firstOrFail();

        // حذف المراجعة
        $review->delete();

        return response()->json([
            'message' => 'تم حذف المراجعة بنجاح',
        ]);
    }

    /**
     * عرض مراجعات المستخدم
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function userReviews(Request $request)
    {
        $reviews = Review::where('customer_id', $request->user()->id)
            ->with(['dish:id,name,image', 'chef:id,name'])
            ->latest('created_at')
            ->paginate(10);

        return response()->json([
            'data' => $reviews,
            'message' => 'تم جلب مراجعاتك بنجاح',
        ]);
    }

    /**
     * عرض مراجعات طاهي معين
     *
     * @param  int  $chefId
     * @return \Illuminate\Http\Response
     */
    public function chefReviews($chefId)
    {
        // التحقق من وجود الطاهي
        $chef = User::where('id', $chefId)
            ->where('type', 'chef')
            ->firstOrFail();

        $reviews = Review::where('chef_id', $chefId)
            ->with(['customer:id,name,profile_image', 'dish:id,name,image'])
            ->latest('created_at')
            ->paginate(10);

        // حساب متوسط التقييم
        $averageRating = Review::where('chef_id', $chefId)->avg('rating');

        return response()->json([
            'data' => [
                'reviews' => $reviews,
                'average_rating' => round($averageRating, 1),
                'total_reviews' => $reviews->total(),
                'chef' => [
                    'id' => $chef->id,
                    'name' => $chef->name,
                ],
            ],
            'message' => 'تم جلب مراجعات الطاهي بنجاح',
        ]);
    }
}
