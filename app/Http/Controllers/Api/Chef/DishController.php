<?php

namespace App\Http\Controllers\Api\Chef;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreNewDish;
use App\Models\Dish;
use App\Models\Chef;
use App\Http\Resources\DishResource;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class DishController extends Controller
{
    public function store(StoreNewDish $request)
    {
        try {
            // $chef = $this->findChef(Auth::id());
            $chef = Chef::first();

            if (!$chef) {
                return ApiResponse::notFound("Chef profile not found. Please create your chef profile first.");
            }

            if (!$chef->is_verified) {
                return ApiResponse::forbidden("Your chef account is not verified.");
            }

            DB::beginTransaction();

            $imagePath = $this->storeImage($request->file('image'), 'dishes');

            $dish = Dish::create([
                'chef_id' => $chef->id,
                'name' => $request->name,
                'description' => $request->description,
                'image' => $imagePath,
                'category_id' => $request->category_id,
                'is_available' => $request->is_available ?? true,
                'total_rate' => 0,
                'avg_rate' => 0.00,
            ]);

            $dish->ingredients()->sync($request->ingredients);

            foreach ($request->sizes as $size) {
                $dish->sizes()->create([
                    'size' => $size['size'],
                    'price' => $size['price'],
                ]);
            }

            DB::commit();

            $dish->load(['chef', 'category', 'sizes']);

            return ApiResponse::created(new DishResource($dish), "Dish created successfully");
        } catch (\Exception $e) {
            return ApiResponse::error('Failed to create dish', 500, $e->getMessage());
        }
    }

    protected function findChef($id)
    {
        return Chef::find($id);
    }

    protected function storeImage(UploadedFile $image, string $folder = 'dishes'): string
    {
        $uniqueName = time() . '_' . Str::random(20) . '.' . $image->getClientOriginalExtension();

        return $image->storeAs($folder, $uniqueName, 'public');
    }

    public function index()
    {
        try {
            // $chef = $this->findChef(Auth::id());
            $chef = Chef::first();
            
            if (!$chef) {
                return ApiResponse::notFound("Chef profile not found.");
            }

            $dishes = Dish::with(['category', 'sizes'])
                ->where('chef_id', $chef->id)
                ->latest()
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Dishes retrieved successfully',
                'data' => DishResource::collection($dishes),
                'meta' => [
                    'total' => $dishes->count(),
                    'chef_id' => $chef->id
                ]
            ], 200);
        } catch (\Exception $e) {
            return ApiResponse::error("Failed to retrieve dishes", 500, $e->getMessage());
        }
    }


    public function show($id)
    {
        try {
            // $chef = $this->findChef(Auth::id());
            $chef = Chef::first();
            
            if (!$chef) {
                return ApiResponse::notFound("Chef profile not found.");
            }

            $dish = Dish::with(['chef', 'category', 'sizes', 'ingredients'])
                ->where('id', $id)
                ->where('chef_id', $chef->id)
                ->first();

            if (!$dish) {
                return ApiResponse::notFound("Dish not found or you do not have permission to access it.");
            }

            return ApiResponse::success(new DishResource($dish), "Dish retrieved successfully");
        } catch (\Exception $e) {
            return ApiResponse::error("Failed to retrieve dish", 500, $e->getMessage());
        }
    }
}
