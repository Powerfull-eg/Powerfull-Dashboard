<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Admin;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class RoleController extends Controller
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
        return view('dashboard.roles.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permissions = $this->getAllowedPermissions();
        $translatePermissions = [
            'index' => __('Main Page'),
            'create' => __('Create Page'),
            'show' => __('Show Page'),
            'edit' => __('Edit Page'),
            'destroy' => __('Delete'),
        ];

        return view('dashboard.roles.create', [
            'permissions' => $permissions,
            'translatePermissions' => $translatePermissions
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
        
        $validated['permissions'] = array_merge($validated['permissions'], Arr::map($this->forcedPermissions, function($value, $key) {
            return Permission::where('name', $value)->first()->id;
        }));

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
        $permissions = $this->getAllowedPermissions();
        $translatePermissions = [
            'index' => __('Main Page'),
            'create' => __('Create Page'),
            'show' => __('Show Page'),
            'edit' => __('Edit Page'),
            'destroy' => __('Delete'),
        ];

        return view('dashboard.roles.edit', [
            'role' => $role,
            'permissions' => $permissions,
            'translatePermissions' => $translatePermissions
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

        $validated['permissions'] = array_merge($validated['permissions'], Arr::map($this->forcedPermissions, function($value, $key) {
            return Permission::where('name', $value)->first()->id;
        }));

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
