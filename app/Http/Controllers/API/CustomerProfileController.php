<?php

namespace App\Http\Controllers\API;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
}

