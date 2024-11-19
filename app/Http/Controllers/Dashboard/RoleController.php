<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Admin;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Route;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dashboard.roles.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permissions = Permission::all()->groupBy(function ($item, $key) {
            return explode('.', $item->name)[1];
        });

        return view('dashboard.roles.create', [
            'permissions' => $permissions,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'unique:roles,name'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['required', 'exists:permissions,id'],
        ]);

        $role = Role::create($validated);

        $role->syncPermissions($validated['permissions'] ?? []);

        return redirect()->route('dashboard.roles.index')
            ->with('success', __(':resource has been created.', ['resource' => __('Role')]));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        $permissions = Permission::all()->groupBy(function ($item, $key) {
            return explode('.', $item->name)[1];
        });

        return view('dashboard.roles.edit', [
            'role' => $role,
            'permissions' => $permissions,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => ['required', 'unique:roles,name,' . $role->id],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['required', 'exists:permissions,id'],
        ]);

        $role->update($validated);
        cache()->flush(); // Clear cache to refresh permissions

        $role->syncPermissions($validated['permissions'] ?? []);

        return redirect()->route('dashboard.roles.index')
            ->with('success', __(':resource has been updated.', ['resource' => __('Role')]));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        if ($role->users()->count() > 0) {
            return redirect()->route('dashboard.roles.index')
                ->with('error', __('Role is used by a user.'));
        }

        $role->delete();

        return redirect()->route('dashboard.roles.index')
            ->with('success', __(':resource has been deleted.', ['resource' => __('Role')]));
    }
    /**
     * Update permissions route
     */
    public function updatePermissionsRoute(string $guard = null)
    {
        // Get all routes
        $routes = Route::getRoutes();
        $allowed_routes = ["index", "create", "edit","destroy","show"];
        
        // Clear All Permissions
        /*
            app(PermissionRegistrar::class)->forgetCachedPermissions();
            Permission::query()->delete();
            return;
        */
        foreach ($routes as $route) {
            $name = $route->getName(); // Get the route name
            $exist = Permission::where('name', $name)->where('guard_name', $guard ?? config('auth.defaults.guard'))->first();
            
            // Specify dashboard routes
            if(strpos($name, 'dashboard.') === false || $exist) {
                continue;
            }

            // Specify allowed routes
            $matches = false;
            foreach($allowed_routes as $route){
                if(strpos($name, $route) !== false){
                    $matches = true;
                    break;
                }
            }

            if($matches == false) continue;
            
            if($name) Permission::findOrCreate($name,$guard ?? config('auth.defaults.guard'));
        }

        return redirect()->back()
            ->with('success', __(':resource permissions has been updated.', ['resource' => __('Role')]));
    }
}
