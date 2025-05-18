<?php

namespace App\Http\Controllers\Dashboard;

use App\Jobs\SendNewAdminNotification;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AdminController extends Controller
{
    private $allowedPermissions;
    private $forcedPermissions = ['dashboard.index'];

    private function getAllowedPermissions() {
        $this->allowedPermissions = [];

        $permissions = Permission::all()->groupBy(function ($item, $key) {
            $notAllowedPermissions = ["impersonate",'language', 'index','memos', 'qr-code'];
            foreach($notAllowedPermissions as $permission){
                if(Str::contains($item->name, $permission, true)){
                    return false;
                }
            }
            return explode('.', $item->name)[1];
        });

        foreach ($permissions as $key => $value) {
            if ($key == 0) continue; 
            $this->allowedPermissions[$key] = $value->pluck('name', 'id')->toArray();
        }
        
        return $this->allowedPermissions;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permissions = $this->getAllowedPermissions();

        $roles = Role::pluck('name', 'id');
        
        $translatePermissions = [
            'index' => __('Main Page'),
            'create' => __('Create Page'),
            'show' => __('Show Page'),
            'edit' => __('Edit Page'),
            'destroy' => __('Delete'),
        ];

        return view('dashboard.admins.index', [
            'permissions' => $permissions,
            'roles' => $roles,
            'translatePermissions' => $translatePermissions
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
            // Add forced permissions
            $validated['permissions'] = array_merge($validated['permissions'], Arr::map($this->forcedPermissions, function($value, $key) {
                return Permission::where('name', $value)->first()->id;
            }));

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
