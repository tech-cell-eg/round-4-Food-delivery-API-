<?php

namespace App\Http\Controllers\API;

use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class CustomerProfileController extends Controller
{
    use ApiResponse;
    public function index(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return ApiResponse::unauthorized('User not authenticated');
        }

        $user->load([
        'cart.items.dish',
        'favorites.dish',
        'addresses',
        'customer',
    ]);


        return ApiResponse::success([
    'user' => [
        'id' => $user->id,
        'name' => $user->name,
        'email' => $user->email,
        'phone' => $user->phone,
        'profile_image' => $user->profile_image,
        'bio' => $user->bio,
        'type' => $user->type,
    ],
    'cart' => $user->cart,
    'favorites' => $user->favorites,
    'addresses' => $user->addresses,
    'payment_methods' => optional($user->customer)->preferred_payment_method,
], 'Profile data retrieved successfully');

    }

public function update(Request $request)
{
    \Log::info('Request Data:', $request->all());

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
        'id', 'name', 'email', 'phone', 'bio', 'profile_image', 'type'
    ]), 'Profile updated successfully');
}




}

