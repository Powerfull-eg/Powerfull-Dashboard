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
        return view('dashboard.gifts.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.gifts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        dd($request);
    }

    /**
     * Display the specified resource.
     */
    // public function show(Request $request,string $id)
    // {
    //     $startDate = $request->startDate ?? null;
    //     $endDate = $request->endDate ?? null;

    //     $gifts = GiftUser::with("gift")->where("shop_id",$id);
    //     $gifts = $startDate ? $gifts->where("created_at",">=",$startDate) : $gifts;
    //     $gifts = $endDate ? $gifts->where("created_at","<=",$endDate) : $gifts;
        
    //     $gifts = $gifts->orderByDesc("created_at")->get();
        
    //     $shop = Shop::where("id",$id);
    //     return view("dashboard.gifts.show", compact('gifts','shop'));
    // }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $gift = Gift::find($id);
        return view('dashboard.gifts.edit',compact('gift'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            "code" => 'required',
            "title_en" => 'required',
            "title_ar" => 'required',
            "message_en" => 'required',
            "message_ar" => 'required',
        ]);
        $gift = Gift::find($id);
        $gift->update($validated);

        return redirect()->route('dashboard.gifts.index')->with('success',__('Gift Updated Successfully'));
    }
    // Show giftusage
    public function show(Request $request,string $id)
    {
        $gift = Gift::find($id);
        return view('dashboard.gifts.show',compact('gift'));
    }
    
    // Show giftusage for shops
    public function showGiftUsage(Request $request,string $id)
    {
        $shop = Shop::findOrFail($id);
        return view('dashboard.gifts.show-gift',compact('shop'));
    }
    // Mark the gift as delivers
    public function deliver(string $id){
        $giftOrder = GiftUser::find($id);
        $giftOrder->update([ 'used_at' => now() ]);
        return redirect()->back()->with('success',__('Gift Marked as Delivered Successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
