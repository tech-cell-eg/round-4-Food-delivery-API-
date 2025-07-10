<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateNewDishRequest;
use App\Http\Requests\UpdateDishRequest;
use App\Models\Category;
use App\Models\Chef;
use App\Models\Dish;
use App\Models\Ingredient;
use App\Traits\MediaHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DishesController extends Controller
{
    use MediaHandler;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = 10;
        $page = $request->get('page', 1);

        $totalDishes = Dish::count();
        $maxPage = ceil($totalDishes / $perPage);

        if ($page > $maxPage && $maxPage > 0) {
            return redirect()->route('admin.dishes.index', ['page' => $maxPage]);
        }

        if ($page < 1) {
            return redirect()->route('admin.dishes.index', ['page' => 1]);
        }

        $dishes = Dish::with(["chef", "category", "sizes", "ingredients", "reviews.customer"])
            ->latest()
            ->paginate($perPage);

        // Calculate statistics
        $stats = [
            'breakfast' => Dish::whereHas('category', function ($query) {
                $query->where('meal_type', 'breakfast');
            })->count(),
            'lunch' => Dish::whereHas('category', function ($query) {
                $query->where('meal_type', 'lunch');
            })->count(),
            'dinner' => Dish::whereHas('category', function ($query) {
                $query->where('meal_type', 'dinner');
            })->count(),
            'available' => Dish::where('is_available', true)->count(),
            'unavailable' => Dish::where('is_available', false)->count(),
            'total' => $totalDishes,
        ];

        return view("dashboard.pages.dishes.index", compact("dishes", "stats"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $chefs = Chef::with('user')->get();
        $categories = Category::all();
        $ingredients = Ingredient::all();

        return view('dashboard.pages.dishes.create', compact('chefs', 'categories', 'ingredients'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateNewDishRequest $request)
    {
        try {
            DB::beginTransaction();

            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $this->storeImage($request->file('image'), "dishes");
            }

            $dish = Dish::create([
                'chef_id' => $request->chef_id,
                'name' => $request->name,
                'description' => $request->description,
                'image' => $imagePath,
                'is_available' => $request->boolean('is_available'),
                'category_id' => $request->category_id,
                "total_rate" => 0,
                "avg_rate" => 0.0,
            ]);

            foreach ($request->sizes as $sizeData) {
                $dish->sizes()->create([
                    'size' => $sizeData['size'],
                    'price' => $sizeData['price'],
                ]);
            }

            if ($request->has('ingredients')) {
                $dish->ingredients()->sync($request->ingredients);
            }

            DB::commit();

            return redirect()->route('admin.dishes.index')
                ->with('success', 'Dish created successfully with ' . count($request->sizes) . ' size(s)!');

        } catch (\Exception $e) {
            DB::rollBack();

            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                $this->deleteImage($imagePath);
            }

            return back()
                ->withInput()
                ->with('error', 'Failed to create dish. Please try again.');
        }
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $dish = Dish::with(['chef.user', 'category', 'sizes', 'ingredients'])->findOrFail($id);
        $chefs = Chef::with('user')->get();
        $categories = Category::all();
        $ingredients = Ingredient::all();

        return view('dashboard.pages.dishes.edit', compact('dish', 'chefs', 'categories', 'ingredients'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDishRequest $request, string $id)
    {
        try {
            DB::beginTransaction();

            $dish = Dish::findOrFail($id);
            $oldImagePath = $dish->image;
            $imagePath = $oldImagePath;
            if ($request->hasFile('image')) {
                $imagePath = $this->storeImage($request->file('image'), "dishes");


                if ($oldImagePath && Storage::disk('public')->exists($oldImagePath)) {
                    $this->deleteImage($oldImagePath);
                }
            }

            $dish->update([
                'chef_id' => $request->chef_id,
                'name' => $request->name,
                'description' => $request->description,
                'image' => $imagePath,
                'is_available' => $request->boolean('is_available'),
                'category_id' => $request->category_id,
            ]);

            // Update sizes - delete old sizes and create new ones
            $dish->sizes()->delete();
            foreach ($request->sizes as $sizeData) {
                $dish->sizes()->create([
                    'size' => $sizeData['size'],
                    'price' => $sizeData['price'],
                ]);
            }

            // Update ingredients
            if ($request->has('ingredients')) {
                $dish->ingredients()->sync($request->ingredients);
            } else {
                $dish->ingredients()->detach();
            }

            DB::commit();

            return redirect()->route('admin.dishes.index')
                ->with('success', 'Dish updated successfully with ' . count($request->sizes) . ' size(s)!');

        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->hasFile('image') && $imagePath !== $oldImagePath && Storage::disk('public')->exists($imagePath)) {
                $this->deleteImage($imagePath);
            }

            return back()
                ->withInput()
                ->with('error', 'Failed to update dish. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $dish = Dish::findOrFail($id);

        if ($dish->image && Storage::disk('public')->exists($dish->image)) {
            $this->deleteImage($dish->image);
        }

        $dish->delete();

        return redirect()->route('admin.dishes.index')->with('success', 'Dish deleted successfully with');
    }
}
