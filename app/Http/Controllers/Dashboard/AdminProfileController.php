<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminProfileController extends Controller
{
    public function edit()
    {
        return view("dashboard.pages.profile.edit");
    }

    public function update(ProfileUpdateRequest $request)
    {
        try {
            $admin = Auth::guard('admin')->user();
            if ($request->filled('password')) {
                if (!$request->filled('current_password')) {
                    return back()->withErrors(['current_password' => 'Current password is required to change password']);
                }

                if (!Hash::check($request->current_password, $admin->password)) {
                    return back()->withErrors(['current_password' => 'Current password is incorrect']);
                }
            }

            $profileImagePath = $admin->profile_image;
            if ($request->hasFile('profile_image')) {
                if ($admin->profile_image && Storage::disk('public')->exists($admin->profile_image)) {
                    Storage::disk('public')->delete($admin->profile_image);
                }

                $profileImagePath = $this->storeImage($request->file('profile_image'), 'users_images');
            }

            $updateData = [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'bio' => $request->bio,
                'profile_image' => $profileImagePath,
            ];

            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($request->password);
            }

            $admin->update($updateData);

            return back()->with('success', 'Profile updated successfully');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Something went wrong. Please try again.']);
        }
    }

    protected function storeImage(UploadedFile $image, string $folder = 'users_images'): string
    {
        $uniqueName = time() . '_' . Str::random(20) . '.' . $image->getClientOriginalExtension();

        return $image->storeAs($folder, $uniqueName, 'public');
    }
}
