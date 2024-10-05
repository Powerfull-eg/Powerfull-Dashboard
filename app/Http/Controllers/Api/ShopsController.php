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
        $shops = Shop::with('device','rates','reactions','data','menu')->get();
        // HOT FIX -- provider_id -> newID --
        foreach($shops as $i => $shop){
            $shops[$i]['newID'] = $shop->provider_id;
        }
        return response()->json($shops);
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
        } else {
            $shopSave = ShopsSave::create([
                'shop_id' => $shop->id,
                'user_id' => $user->id
            ]);

            return response()->json(ShopsSave::where('user_id', $user->id)->pluck('shop_id'));
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
        return response()->json(ShopsSave::where('user_id', $user->id)->pluck('shop_id'));
    }

    /**
     * get Saving shops.
     */
    public function getSavingShops()
    {
        $user = Auth::guard('api')->user();        
        if(!$user) return response()->json('Unauthenticated', 401);
        return response()->json(ShopsSave::where('user_id', $user->id)->with('shop')->get());
    }

    /**
     * Add Shop comment
     */
    public function addComment(Request $request)
    {
        $request->validate([
            'shop_id' => 'required|exists:shops,id',
            'comment' => 'required|string|max:200',
            'rate' => 'numeric|between:1,5'
        ]);

        $shop = Shop::findOrFail($request->shop_id);
        $user = Auth::guard('api')->user();

        if(!$user) return response()->json('Unauthenticated', 401);

        $shop->rates()->create([
            'shop_id' => $shop->id,
            'user_id' => $user->id,
            'comment' => $request->comment,
            'rate' => $request->rate
        ]);

        return response()->json($shop->rates()->where('hidden', 'no')->get());
    }

    /**
     * Add Shop Reaction
     */
    public function addReact(Request $request)
    {
        $request->validate([
            'shop_id' => 'required|exists:shops,id',
            'reaction' => 'required|string|in:like,dislike,love,angry,sad,surprised',
        ]);

        $shop = Shop::findOrFail($request->shop_id);
        $user = Auth::guard('api')->user();

        if(!$user) return response()->json('Unauthenticated', 401);
        // check if user already reacted
        if($shop->reactions()->where('user_id', $user->id)->where('shop_id', $request->shop_id)->exists()) {
            $shop->reactions()->where('user_id', $user->id)->where('shop_id', $request->shop_id)->update([
                'reaction' => $request->reaction
            ]);
        }else{
            $shop->reactions()->create([
                'shop_id' => $shop->id,
                'user_id' => $user->id,
                'reaction' => $request->reaction
            ]);
        }

        return response()->json($shop->reactions()->get());
    }
}