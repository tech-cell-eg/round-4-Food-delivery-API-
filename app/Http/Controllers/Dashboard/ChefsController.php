<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateNewChefRequest;
use App\Http\Requests\UpdateChefRequest;
use App\Models\Chef;
use App\Models\User;
use App\Traits\MediaHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ChefsController extends Controller
{
    use MediaHandler;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $chefs = Chef::with(['user', 'dishes', 'reviews'])
            ->withCount(['dishes', 'reviews'])
            ->latest()
            ->paginate(10);

        // Stats for dashboard
        $chefsCount = Chef::count();
        $verifiedChefs = Chef::where('is_verified', true)->count();
        $activeChefs = Chef::whereHas('user', function ($query) {
            $query->whereNotNull('email_verified_at');
        })->count();

        // Calculate additional stats for each chef
        foreach ($chefs as $chef) {
            // Calculate average rating
            $chef->average_rating = $chef->reviews()->avg('rating') ?? 0;
            
            // Calculate total orders through dishes
            $chef->total_orders = $chef->dishes()
                ->withCount('orderItems')
                ->get()
                ->sum('order_items_count');
                
            // Calculate total earnings (estimated)
            $chef->total_earnings = $chef->dishes()
                ->join('order_items', 'dishes.id', '=', 'order_items.dish_id')
                ->sum('order_items.total_price');
        }

        return view('dashboard.pages.chefs.index', compact(
            'chefs',
            'chefsCount',
            'verifiedChefs',
            'activeChefs'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.pages.chefs.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateNewChefRequest $request)
    {
        try {
            DB::beginTransaction();

            $profileImagePath = null;
            if ($request->hasFile('profile_image')) {
                $profileImagePath = $this->storeImage($request->file('profile_image'), 'users_images');
            }

            $user = $this->storeNewUserChef($request, $profileImagePath);
            if($request->boolean("email_verified")) {
                $user->markEmailAsVerified();
            }

            Chef::create([
                'id' => $user->id,
                'national_id' => $request->national_id,
                'description' => $request->description,
                'location' => $request->location,
                'balance' => $request->balance ?? 0.00,
                'is_verified' => $request->boolean('is_verified'),
            ]);

            DB::commit();

            return redirect()->route('admin.chefs.index')
                ->with('success', 'Chef created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();

            if ($profileImagePath) {
                $this->deleteImage($profileImagePath);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error creating chef: ' . $e->getMessage());
        }
    }

    protected function storeNewUserChef($request, $profileImagePath): User
    {
        return User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'bio' => $request->bio,
            'profile_image' => $profileImagePath,
            'type' => 'chef',
            'address' => $request->address,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $chef = Chef::with('user')->findOrFail($id);

        return view('dashboard.pages.chefs.edit', compact('chef'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateChefRequest $request, string $id)
    {
        try {
            DB::beginTransaction();

            $chef = Chef::with('user')->findOrFail($id);
            $user = $chef->user;

            $profileImagePath = $user->profile_image;
            if ($request->hasFile('profile_image')) {
                if ($profileImagePath) {
                    $this->deleteImage($profileImagePath);
                }

                $profileImagePath = $this->storeImage($request->file('profile_image'), 'users_images');
            }

            $userData = [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'bio' => $request->bio,
                'profile_image' => $profileImagePath,
                'address' => $request->address,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
            ];

            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            $user->update($userData);

            if ($request->boolean('email_verified')) {
                $user->markEmailAsVerified();
            } else {
                $user->email_verified_at = null;
                $user->save();
            }

            $chef->update([
                'national_id' => $request->national_id,
                'description' => $request->description,
                'location' => $request->location,
                'balance' => $request->balance ?? $chef->balance,
                'is_verified' => $request->boolean('is_verified'),
            ]);

            DB::commit();

            return redirect()->route('admin.chefs.index')
                ->with('success', 'Chef updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error updating chef: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();

            $chef = Chef::with('user')->findOrFail($id);
            $user = $chef->user;

            if ($user && $user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
                $this->deleteImage($user->profile_image);
            }

            $chef->delete();
            $user->delete();

            DB::commit();

            return redirect()->route('admin.chefs.index')
                ->with('success', 'Chef deleted successfully!');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Error deleting chef: ' . $e->getMessage());
        }
    }
}
