<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Ingredient;
use Illuminate\Http\Request;

class IngredientsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = 10;
        $page = $request->get('page', 1);

        $totalIngredients = Ingredient::count();
        $maxPage = ceil($totalIngredients / $perPage);

        if ($page > $maxPage && $maxPage > 0) {
            return redirect()->route('admin.ingredients.index', ['page' => $maxPage]);
        }

        if ($page < 1) {
            return redirect()->route('admin.ingredients.index', ['page' => 1]);
        }

        $ingredients = Ingredient::paginate($perPage);

        $stats = [
            'basic' => Ingredient::where('type', 'basic')->count(),
            'fruit' => Ingredient::where('type', 'fruit')->count(),
            'total' => Ingredient::count(),
        ];

        return view("dashboard.pages.ingredients.index", compact("ingredients", "stats"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("dashboard.pages.ingredients.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255|unique:ingredients,name',
            'type' => 'required|string|in:basic,fruit',
        ]);

        Ingredient::create($request->only(['name', 'type']));

        return redirect()->route('admin.ingredients.index')
            ->with('success', 'Ingredient created successfully!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $ingredient = Ingredient::findOrFail($id);
        return view("dashboard.pages.ingredients.edit", compact("ingredient"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $ingredient = Ingredient::findOrFail($id);

        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255|unique:ingredients,name,' . $id,
            'type' => 'required|string|in:basic,fruit',
        ]);

        // Update the ingredient
        $ingredient->update($request->only(['name', 'type']));

        return redirect()->route("admin.ingredients.index")->with("success", "Ingredient updated successfully");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $ingredient = Ingredient::findOrFail($id);
        $ingredient->delete();

        return redirect()->route("admin.ingredients.index")->with("success", "Ingredient deleted successfully");
    }
}
