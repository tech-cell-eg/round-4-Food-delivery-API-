<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::all();

        return view("dashboard.pages.roles.index")->with("roles", $roles);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permissions = Permission::all();

        return view("dashboard.pages.roles.create")->with("permissions", $permissions);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|unique:roles,name|max:255',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $role = Role::create([
            'name' => $validatedData['name'],
            'guard_name' => "admin",

        ]);

        if (isset($validatedData['permissions'])) {
            $role->syncPermissions($validatedData['permissions']);
        }

        return redirect()->route('admin.roles.index')->with('success', 'Role created successfully with permissions.');

    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $role = Role::findOrFail($id);

        $permissions = Permission::all();
        $rolePermissions = $role->permissions->pluck('id')->toArray();

        return view('dashboard.pages.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $role = Role::findOrFail($id);

        $role->name = $request->name;
        $role->save();

        $permissionNames = $request->permissions ?? [];
        $permissions = Permission::whereIn('name', $permissionNames)->get();
        $role->syncPermissions($permissions);

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $role = Role::findById($id);
        if($role){
            $role->delete();
            return redirect()->route('admin.roles.index')->with('success', 'Role deleted successfully');
        }
        return redirect()->route('admin.roles.index')->with('error', 'Role not found');
    }
}
