<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permissions = Permission::all();

        return view("dashboard.pages.permissions.index")->with("permissions", $permissions);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("dashboard.pages.permissions.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            "name" => "required",
        ]);

        $permission = Permission::create([
            "name" => $validatedData['name'],
            'guard_name' => "admin",

        ]);

        return redirect()->route("admin.permissions.index")->with("success", "Permission created successfully");

    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $permission = Permission::findById($id);

        if($permission){
            return view("dashboard.pages.permissions.edit")->with("permission", $permission);
        }

        return redirect()->route('admin.permissions.index')->with('error', 'Permission not found');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            "name" => "required",
        ]);

        $permission = Permission::findById($id);
        if(! $permission){
            return redirect()->route('admin.permissions.index')->with('error', 'Permission not found');
        }

        $permission->update([
            "name" => $validatedData['name'],
        ]);

        return redirect()->route('admin.permissions.index')->with('success', 'Permission deleted successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $permission = Permission::findById($id);
        if($permission){
            $permission->delete();
            return redirect()->route('admin.permissions.index')->with('success', 'Permission deleted successfully');
        }
        return redirect()->route('admin.permissions.index')->with('error', 'Permission not found');
    }
}
