<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dish;
use Illuminate\Http\Request;

class DishController extends Controller
{
    /**
     * Display a listing of dishes.
     */
    public function index(Request $request)
    {
        $dishes = Dish::with(['chef.user', 'category'])
            ->latest()
            ->paginate(20);

        return view('admin.dishes.index', [
            'dishes' => $dishes,
        ]);
    }
}
