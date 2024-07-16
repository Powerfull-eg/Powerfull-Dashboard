<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Gift;
use App\Models\GiftUser;
use App\Models\Shop;
use Illuminate\Http\Request;

class GiftsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function show(Request $request,string $id)
    {
        $startDate = $request->startDate ?? null;
        $endDate = $request->endDate ?? null;

        $gifts = GiftUser::with("gift")->where("shop_id",$id);
        $gifts = $startDate ? $gifts->where("created_at",">=",$startDate) : $gifts;
        $gifts = $endDate ? $gifts->where("created_at","<=",$endDate) : $gifts;
        
        $gifts = $gifts->orderByDesc("created_at")->get();
        
        $shop = Shop::where("provider_id",$id);
        return view("dashboard.gifts.show", compact('gifts','shop'));
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
}
