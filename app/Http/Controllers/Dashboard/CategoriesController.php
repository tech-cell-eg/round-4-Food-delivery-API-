<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateNewCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use App\Traits\MediaHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoriesController extends Controller
{
    use MediaHandler;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = 10;
        $page = $request->get('page', 1);

        $totalCategories = Category::count();
        $maxPage = ceil($totalCategories / $perPage);

        if ($page > $maxPage && $maxPage > 0) {
            return redirect()->route('admin.categories.index', ['page' => $maxPage]);
        }

        if ($page < 1) {
            return redirect()->route('admin.categories.index', ['page' => 1]);
        }

        $categories = Category::orderBy("created_at", "DESC")->paginate($perPage);

        // Calculate stats for meal types
        $stats = [
            'breakfast' => Category::where('meal_type', 'breakfast')->count(),
            'lunch' => Category::where('meal_type', 'lunch')->count(),
            'dinner' => Category::where('meal_type', 'dinner')->count(),
        ];

        return view("dashboard.pages.categories.index", compact("categories", "stats"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("dashboard.pages.categories.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateNewCategoryRequest $request)
    {
        $data = $request->only(['name', 'meal_type']);

        if ($request->hasFile('image')) {
            $data['image'] = $this->storeImage($request->file('image'), 'categories');
        }

        Category::create($data);

        return redirect()->route("admin.categories.index")->with("success", "Category created successfully");
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $category = Category::findOrFail($id);

        return view("dashboard.pages.categories.edit", compact("category"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, string $id)
    {
        $category = Category::findOrFail($id);

        $data = $request->only(['name', 'meal_type']);

        if ($request->hasFile('image')) {
            if ($category->image && Storage::disk('public')->exists($category->image)) {
                $this->deleteImage($category->image);
            }

            $data['image'] = $this->storeImage($request->file('image'), 'categories');
        }

        $category->update($data);

        return redirect()->route("admin.categories.index")->with("success", "Category updated successfully");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::findOrFail($id);

        if ($category->image && Storage::disk('public')->exists($category->image)) {
            $this->deleteImage($category->image);
        }

        $category->delete();

        return redirect()->route("admin.categories.index")->with("success", "Category deleted successfully");
    }
}
