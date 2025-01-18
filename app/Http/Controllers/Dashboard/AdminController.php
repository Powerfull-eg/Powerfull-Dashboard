<?php

namespace App\Http\Controllers\Dashboard;

use App\Jobs\SendNewAdminNotification;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permissions = Permission::all()->groupBy(function ($item, $key) {
            return explode('.', $item->name)[1];
        });

        $roles = Role::pluck('name', 'id');
        
        return view('dashboard.admins.index', [
            'permissions' => $permissions,
            'roles' => $roles,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::pluck('name', 'id');

        return view('dashboard.admins.create', [
            'roles' => $roles,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:125',
            'last_name' => 'required|string|max:125',
            'email' => 'required|string|email|max:255|unique:admins',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|exists:roles,id',
        ]);

        $admin = Admin::create([
            'name' => $validated['first_name'] . ' ' . $validated['last_name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $admin->assignRole($validated['role']);

        SendNewAdminNotification::dispatch($admin, $validated['password']);

        return redirect()->route('dashboard.admins.index')->with('success', __(':resource has been created.', ['resource' => __('Admin')]));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Admin $admin)
    {
        $roles = Role::pluck('name', 'id');

        return view('dashboard.admins.edit', [
            'admin' => $admin,
            'roles' => $roles,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Admin $admin)
    {
        $validated = $request->validate([
            'role' => 'required|exists:roles,id',
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|string|email|max:255',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        cache()->flush();

        $admin->syncRoles($validated['role']);

        $admin->update([
            'name' => $validated['name'] ?? $admin->name,
            'email' => $validated['email'] ?? $admin->email,
            'password' => $validated['password'] ? Hash::make($validated['password']) : $admin->password,
        ]);

        return redirect()->route('dashboard.admins.index')->with('success', __(':resource has been updated.', ['resource' => __('Admin')]));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Admin $admin)
    {
        if ($admin->id === auth()->user()->id) {
            return back()->with('error', __('You cannot delete yourself.'));
        }

        $admin->delete();

        return back()->with('success', __(':resource has been deleted.', ['resource' => __('Admin')]));
    }

// Create a new account with permissions
    public function createWithPermissions(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:125', 
            'last_name' => 'required|string|max:125',
            'email' => 'required|string|email|max:255|unique:admins',
            'password' => 'required|string|min:8|confirmed',
            'permissions' => 'required_if:role,0|array',
            'permissions.*' => 'required_if:role,0|exists:permissions,id',
            'role' => 'required',
        ]);

        // Check if role is exist or new
        // If new create role
        if($validated['role'] == 0) {
            $role = Role::create([
                'name' => Str::random(10),
                'permissions' => $validated['permissions']
            ]);

            $role->syncPermissions($validated['permissions'] ?? []);
        // If role is exist
        }else{
            $role = Role::findOrfail($validated['role']);
        }
        // Create admin account
        $admin = Admin::create([
            'name' => $validated['first_name'] . ' ' . $validated['last_name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password'])
        ]);

        // Assign role to admin
        $admin->assignRole($role);

        return redirect()->route('dashboard.admins.index')->with('success', __(':resource has been created.', ['resource' => __('Admin')]));

    }
}
