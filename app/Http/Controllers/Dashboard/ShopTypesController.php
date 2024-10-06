<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShopsType;

class ShopTypesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dashboard.shops.types.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.shops.types.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type_ar_name' => 'required|string|max:20',
            'type_en_name' => 'required|string|max:20',
            'type_icon' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'access_ar_name' => 'required|string|max:20',
            'access_en_name' => 'required|string|max:20',
            'access_icon' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        
        $validated['type_icon'] = time() .'-' . $request->file('type_icon')->getClientOriginalName();
        $request->file('type_icon')->storePubliclyAs("public/types/", $validated['type_icon']);

        $validated['access_icon'] = time() .'-' . $request->file('access_icon')->getClientOriginalName();
        $request->file('access_icon')->storePubliclyAs("public/types", $validated['access_icon']);
        $type = ShopsType::create($validated);
        
        return $type ? 
        redirect(route('dashboard.shop-types.index'))->with('success', __("Shop Type Created Successfully"))
        : redirect()->back()->with('error', __("Shop Type Created Failed"));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $type = ShopsType::find($id);
        return view('dashboard.shops.types.edit',['type' => $type]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'type_ar_name' => 'required|string|max:20',
            'type_en_name' => 'required|string|max:20',
            'type_icon' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'access_ar_name' => 'required|string|max:20',
            'access_en_name' => 'required|string|max:20',
            'access_icon' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        
        if($request->type_icon){
            $validated['type_icon'] = time() .'-' . $request->file('type_icon')->getClientOriginalName();
            $request->file('type_icon')->storePubliclyAs("public/types/", $validated['type_icon']);
        }

        if($request->access_icon){
            $validated['access_icon'] = time() .'-' . $request->file('access_icon')->getClientOriginalName();
            $request->file('access_icon')->storePubliclyAs("public/types", $validated['access_icon']);
        }
        $type = ShopsType::findOrfail($id)->update($validated);
        return $type ? 
        redirect(route('dashboard.shop-types.index'))->with('success', __("Shop Type Updated Successfully"))
        : redirect()->back()->with('error', __("Shop Type Updated Failed"));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $type = ShopsType::findOrfail($id);
        $type->delete();

        return redirect()->back()->with('success', __("Shop Type Deleted Successfully"));
    }
}
