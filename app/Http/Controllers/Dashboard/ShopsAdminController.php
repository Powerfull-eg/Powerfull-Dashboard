<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\ShopsAdmin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Shop;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ShopsAdminController extends Controller
{
    public $allowedPermissions;

    public function __construct()
    {
        $this->allowedPermissions = [
            'dashboard.index',
            'dashboard.shops.operations.show',
            'dashboard.shops.show',
            'dashboard.gifts-show',
            'dashboard.vouchers.show'
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permissions = Permission::whereIn('name', $this->allowedPermissions)->pluck('name', 'id');
        $shops = Shop::pluck('name', 'id');
        return view('dashboard.admins.shops.index', compact('permissions', 'shops'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // $permissions = Permission::whereIn('name', $this->allowedPermissions)->pluck('name', 'id');
        // $shops = Shop::pluck('name', 'id');
        // return view('dashboard.admins.shops.create', compact('permissions', 'shops'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->input());
        $validated = $request->validate([
           "first_name" => "required|string|max:125",
           "last_name" => "required|string|max:125",
           "email" => "required|string|email|max:255|unique:admins",
           "password" => "required|string|min:8|confirmed",
           "shop" => "required|exists:shops,id",
        ]);

        $role = Role::where('name', 'shopAdmin')->first();
        
        if(!$role) {
            $role = Role::create(['name' => 'shopAdmin']);
            $role->syncPermissions($this->allowedPermissions);
        }

        $admin = Admin::create([
            'name' => $validated['first_name'] . ' ' . $validated['last_name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $admin->assignRole($role);
        $shop = Shop::find($validated['shop']);

        $shop->admins()->attach($admin);
        
        return redirect()->route('dashboard.shop-admins.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(ShopsAdmin $shopsAdmin)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ShopsAdmin $shopsAdmin)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ShopsAdmin $shopsAdmin)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ShopsAdmin $shopsAdmin)
    {
        //
    }
}
