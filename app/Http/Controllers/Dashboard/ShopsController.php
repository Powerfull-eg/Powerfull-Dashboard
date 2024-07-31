<?php

namespace App\Http\Controllers\Dashboard;

use App\Livewire\ShopOperationsTable;
use App\Models\Shop;
use App\Http\Requests\StoreShopRequest;
use App\Http\Requests\UpdateShopRequest;
use App\Models\Station;
use Illuminate\Http\Request;

use function Pest\Laravel\json;

class ShopsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $startDate = $request->startDate ?? null;
        $endDate = $request->endDate ?? null;
        return view("dashboard.shops.index", compact('startDate','endDate'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("dashboard.shops.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreShopRequest $request)
    {   
        // 
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request,string $id)
    {
        // Date Filter
        $startDate = $request->startDate ?? null;
        $endDate = $request->endDate ?? null;

        $shop = Shop::with(['device','operations','gifts'])->find($id);
        $totalAmount = 0;
        $totalHours = 0;
        $totalGifts = $startDate || $endDate ? $shop->gifts->where('created_at','>=',$startDate)->where('created_at','<=',$endDate)->count() : $shop->gifts->count();
        
        $operations = $startDate || $endDate ? $shop->device->operations->where('created_at','>=',$startDate)->where('created_at','<=',$endDate) : $shop->device->operations;
        // $operations[] = $endDate ? $shop->device->operations->where('created_at','<=',$endDate) : null;
        
        foreach($operations as $operation){
            $totalAmount += ($operation->amount ?? 0);
            $totalHours += ($operation->returnTime && $operation->borrowTime ? floatval((strtotime($operation->returnTime) - strtotime($operation->borrowTime) )/ 60 /60) : 0);
        }

        return view("dashboard.shops.show",compact('shop','totalAmount','totalHours','totalGifts','startDate','endDate'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Shop $shop)
    {
        $shop = Shop::find($shop->id);
        return view("dashboard.shops.edit",compact('shop'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,string $id)
    {
        $validated = $request->validate([
                "name" => "required|string|max:255",
                "phone" => "required|string|starts_with:010,011,012,015|size:11",
        ]);
        $admin = auth('admins')->user()->id;
        $validated["updated_by"] = $admin;
        
        $shop = Shop::findOrfail($id)->update($validated);
        
        return $shop 
        ? redirect(route('dashboard.shops.index'))->with('success', __("Shop Updated Successfully"))
        : redirect()->back()->with('error', __("Failed Update Shop")); 
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Shop $shop)
    {
        $shop =  Shop::find($shop->id);
        $shop->delete();
        return redirect()->back();
    }
}
