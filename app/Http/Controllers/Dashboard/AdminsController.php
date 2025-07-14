<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateNewAdminRequest;
use App\Http\Requests\UpdateAdminRequest;
use App\Models\Admin;
use App\Models\User;
use App\Traits\MediaHandler;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class AdminsController extends Controller
{
    use MediaHandler;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $authUser = Auth::guard("admin")->user();

        $admins = Admin::where("id", "!=", $authUser->id)->with(["user", "roles"])->get();

        return view("dashboard.pages.admins.index")->with("admins", $admins);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();

        return view("dashboard.pages.admins.create")->with("roles", $roles);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateNewAdminRequest $request)
    {
        $validatedData = $request->validated();

        DB::beginTransaction();

        try {
            $userId = $this->storeNewUserAdmin($validatedData, $request);

            $admin = Admin::create([
                "id" => $userId,
                "status" => $validatedData["status"],
            ]);

            if (isset($validatedData['roles']) && is_array($validatedData['roles'])) {
                $admin->syncRoles($validatedData['roles']);
            }

            DB::commit();

            return redirect()
                ->route('admin.admins.index')
                ->with('success', 'Admin created successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }


    protected function storeNewUserAdmin(Array $validatedData, $request): int
    {
        $userData = [
            "name" => $validatedData["name"],
            "email" => $validatedData["email"],
            "phone" => $validatedData["phone"],
            "password" => bcrypt($validatedData["password"]),
            "bio" => ! empty($validatedData["bio"]) ? $validatedData["bio"] : null,
            "type" => "admin",
            "email_verified_at" => now(),
        ];

        if($request->hasFile('profile_image')) {
            $userData['profile_image'] = $this->storeImage($request->file('profile_image'), 'users_images');
        }

        $user = User::create($userData);

        return $user->id;
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $admin = Admin::findOrFail($id);

        if(! $admin) {
            return redirect()->route('admin.admins.index')->with('error', 'Admin not found');
        }

        $admin->load(["user", "roles"]);

        $roles = Role::all();

        return view("dashboard.pages.admins.edit", get_defined_vars());
    }

    public function update(UpdateAdminRequest $request, string $id)
    {
        $validatedData = $request->validated();

        $admin = Admin::findOrFail($id);

        $userData = [
            "name" => $validatedData["name"],
            "email" => $validatedData["email"],
            "phone" => $validatedData["phone"],
            "bio" => $validatedData["bio"] ?? null,
        ];

        if (isset($validatedData["password"])) {
            $userData['password'] = bcrypt($validatedData["password"]);
        }

        if ($request->hasFile('profile_image')) {
            if ($admin->user && $admin->user->profile_image && Storage::disk('public')->exists($admin->user->profile_image)) {
                $this->deleteImage($admin->user->profile_image);
            }

            $imagePath = $this->storeImage($request->file('profile_image'), 'users_images');
            $userData['profile_image'] = $imagePath;
        }

        DB::beginTransaction();

        try {
            if ($admin->user) {
                $admin->user->update($userData);
            } else {
                throw new \Exception('User relation is missing for this admin.');
            }

            if (array_key_exists("status", $validatedData)) {
                $admin->status = $validatedData["status"];
                $admin->save();
            }

            if (! empty($validatedData['roles']) && is_array($validatedData['roles'])) {
                $admin->syncRoles($validatedData['roles']);
            }

            DB::commit();

            return redirect()
                ->route('admin.admins.index')
                ->with('success', 'Admin updated successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $admin = Admin::findOrFail($id);

        if ($admin->user && $admin->user->profile_image && Storage::disk('public')->exists($admin->user->profile_image)) {
            $this->deleteImage($admin->user->profile_image);
        }

        if ($admin->user) {
            $admin->user->delete();
        }

        return redirect()
            ->route('admin.admins.index')
            ->with('success', 'Admin Deleted successfully');
    }
}
