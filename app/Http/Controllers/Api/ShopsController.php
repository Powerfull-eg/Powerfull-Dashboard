<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\ShopsSave;
use Illuminate\Support\Facades\Auth;

class ShopsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Shop::with('device','rates','reactions','data','menu')->get());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Saving shops.
     */
    public function save(Request $request)
    {
        $request->validate([
            'shop_id' => 'required'
        ]);

        $shop = Shop::findOrFail($request->shop_id);
        $user = Auth::guard('api')->user();
        
        if(!$user) return response()->json('Unauthenticated', 401);
        
        $shopSave = ShopsSave::where('shop_id', $shop->id)->where('user_id', $user->id)->first();
        if ($shopSave) {
            $shopSave->delete();
            return response()->json(['isSaved' => false]);
        } else {
            $shopSave = ShopsSave::create([
                'shop_id' => $shop->id,
                'user_id' => $user->id
            ]);
            return response()->json(['isSaved' => true]);
        }
    }

    /**
     * Check if shop is saved.
     */
    public function checkSave(Request $request)
    {
        $request->validate([
            'shop_id' => 'required'
        ]);

        $shop = Shop::findOrFail($request->shop_id);
        $user = Auth::guard('api')->user();
        
        if(!$user) return response()->json('Unauthenticated', 401);
        
        $shopSave = ShopsSave::where('shop_id', $shop->id)->where('user_id', $user->id)->first();
            return response()->json(['isSaved' => ($shopSave ? true : false)]);
    }
}
