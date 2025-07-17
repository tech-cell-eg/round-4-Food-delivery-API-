<?php

namespace App\Http\Controllers\API;

use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;

class CustomerProfileController extends Controller
{
    use ApiResponse;
    public function index(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return ApiResponse::unauthorized('User not authenticated');
        }

        // Always load these relations
        $user->load([
            'cart.items.dish',
            'favorites.dish',
            'addresses',
            'customer',
            'chef', // in case type is 'chef'
        ]);

        // Base user info
        $userData = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'profile_image' => $user->profile_image,
            'bio' => $user->bio,
            'type' => $user->type,
        ];

        // Add additional info based on user type
        if ($user->type === 'customer') {
            $extraData = [
                'cart' => $user->cart,
                'favorites' => $user->favorites,
                'addresses' => $user->addresses,
                'payment_methods' => optional($user->customer)->preferred_payment_method,
            ];
        } elseif ($user->type === 'chef') {
            $extraData = [
                'chef_profile' => $user->chef, // Return chef model data
            ];
        } else {
            $extraData = [];
        }

        return ApiResponse::success(
            array_merge(['user' => $userData], $extraData),
            'Profile data retrieved successfully'
        );
    }


    public function update(Request $request)
    {

        $user = $request->user();

        if (!$user) {
            return ApiResponse::unauthorized('User not authenticated');
        }

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
            'phone' => 'sometimes|string|max:20',
            'bio' => 'nullable|string',
            'profile_image' => 'nullable|image|max:2048',
        ]);

        // Handle image upload
        if ($request->hasFile('profile_image')) {
            // Delete old image if it exists
            if ($user->profile_image && file_exists(public_path($user->profile_image))) {
                unlink(public_path($user->profile_image));
            }

            $image = $request->file('profile_image');
            $filename = $user->type . '-' . time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('profile_images'), $filename);
            $validated['profile_image'] = 'profile_images/' . $filename;
        }

        // Update user with all validated data (including image path if uploaded)
        $user->update($validated);

        return ApiResponse::success($user->only([
            'id',
            'name',
            'email',
            'phone',
            'bio',
            'profile_image',
            'type'
        ]), 'Profile updated successfully');
    }

    public function profileInfoUpdate(Request $request)
    {
        $user = User::find(Auth::id());

        if (!$user || $user->type !== 'customer') {
            return ApiResponse::unauthorized('User not authenticated');
        }

        $validated = $request->validate([
            'first_name'    => 'nullable|string|max:255',
            'last_name'     => 'nullable|string|max:255',
            'address'       => 'nullable|string|max:255',
            'phone'         => 'nullable|string|max:20',
            'bio'           => 'nullable|string',
        ]);

        //Upsate Profile image if included in request
        if ($request->hasFile('profile_image')) {
            $image = $request->file('profile_image');
            $filename = $user->type . '-' . time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('profile_images'), $filename);
            $validated['profile_image'] = 'profile_images/' . $filename;
        }

        $validated['id'] = $user->id;

        $customer = Customer::firstOrCreate(['id' => $user->id]);
        $customer->update($validated);

        return ApiResponse::success($user->load('customer'), 'Profile information updated successfully');
    }

    public function userInfoUpdate(Request $request)
    {
        $user = User::find(Auth::id());

        if (!$user) {
            return ApiResponse::unauthorized('User not authenticated');
        }

        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string',
        ]);

        $user->update($validated);

        return ApiResponse::success($user->load('customer'), 'Profile updated successfully');
    }
}
