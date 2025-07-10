<?php
namespace App\Http\Controllers\API;

use App\Models\User;
use App\Models\PasswordOtp;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class OtpLoginController extends Controller
{
    public function sendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return ApiResponse::notFound('User not found');
        }

        $otp = 1234;

        PasswordOtp::updateOrCreate(
        ['email' => $request->email],
            [
                'otp' => $otp,
                'expires_at' => Carbon::now()->addMinutes(10),
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

        if (!$record ||  Carbon::parse($record->expires_at)->isPast()) {
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
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $record = PasswordOtp::where('email', $request->email)->where('otp', $request->otp)->first();

        if (!$record || Carbon::parse($record->expires_at)->isPast()) {
            return ApiResponse::error('Invalid or expired OTP');
        }

        $user = User::where('email', $request->email)->firstOrFail();
        $user->update(['password' => Hash::make($request->password)]);

        // Delete OTP after use
        $record->delete();

        return ApiResponse::success(null, 'Password reset successfully');
    }

    public function verifyEmail(Request $request)
    {
        $request->validate(['email' => 'required|email', 'otp' => 'required|string']);

        $record = PasswordOtp::where('email', $request->email)->where('otp', $request->otp)->first();
        $user = User::where('email', $request->email)->first();
        
        if (!$user) {
            return ApiResponse::notFound('User not found');
        }

        if (!$record || Carbon::parse($record->expires_at)->isPast()) {
            return ApiResponse::error('Invalid or expired OTP');
        }

        return ApiResponse::success(null, 'OTP verified successfully to to login');
    }
}

