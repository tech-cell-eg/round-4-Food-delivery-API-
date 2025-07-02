<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PasswordOtp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Carbon;
use App\Helpers\ApiResponse;

class OtpLoginController extends Controller
{
    public function sendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return ApiResponse::notFound('User not found');
        }

        $otp = rand(100000, 999999);

        PasswordOtp::updateOrCreate(
        ['email' => $request->email],
            [
                'otp' => $otp,
                'expires_at' => Carbon::now()->addMinutes(5),
            ]
        );

        Mail::raw("Your login OTP is: $otp", function ($message) use ($request) {
            $message->to($request->email)->subject('Your Login OTP');
        });

        return ApiResponse::success(null, 'OTP sent to your email');
    }


    public function loginWithOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|string'
        ]);

        $record = PasswordOtp::where('email', $request->email)
            ->where('otp', $request->otp)
            ->first();

        if (!$record || $record->expires_at->isPast()) {
            return ApiResponse::error('Invalid or expired OTP');
        }

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return ApiResponse::notFound('User not found');
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        // Delete OTP after login
        $record->delete();

        return ApiResponse::success([
            'message' => 'Login successful',
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }
}

