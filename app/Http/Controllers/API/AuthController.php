<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
    use ApiResponse;
    public function register(UserRequest $request)
    {
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'type'     => 'customer',
            'phone'    => $request->phone,
        
        ]);

        if ($request->hasFile('profile_image')) {
        $image = $request->file('profile_image');
        $filename = $user->type . '-' . time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('profile_images'), $filename);
        $validated['profile_image'] = 'profile_images/' . $filename;
    }


        // if ($request->type === 'chef') {

        //     $user->chef()->create([
        //         'speciality'       => $request->speciality ?? 'Tourism and hotels',
        //         'experience_years' => $request->experience_years ?? 1,
        //     ]);
        // }

        // if ($request->type === 'customer') {
        //     $user->customer()->create([
        //         'preferred_payment_method' => $request->preferred_payment_method ?? 'cash_on_delivery',
        //     ]);
        // }

        $user->customer()->create([
            
                'preferred_payment_method' => $request->preferred_payment_method ?? null,
            ]);
        return ApiResponse::created([
            'user'         => $user,
        ], 'User created successfully');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt($credentials)) {
            return ApiResponse::unauthorized('Invalid credentials');
        }

        $user  = User::where('email', $credentials['email'])->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;

        return ApiResponse::success([
            'user'         => $user,
            'access_token' => $token,
            'token_type'   => 'Bearer',
        ], 'Login successful');
    }

public function logout(Request $request)
{
    $user = $request->user();

    if (!$user) {
        return ApiResponse::error('Unauthenticated', 401);
    }

    // Revoke all tokens
    $user->tokens()->delete();

    return ApiResponse::success(null, 'Logout successful');
}



    public function user(Request $request)
    {
        return ApiResponse::success([
            'user' => $request->user(),
        ]);
    }
}
