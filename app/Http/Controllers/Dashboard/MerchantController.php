<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Merchant;
use App\Http\Requests\StoreMerchantRequest;
use App\Http\Requests\UpdateMerchantRequest;

use function Pest\Laravel\json;

class MerchantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view("dashboard.merchants.index");
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("dashboard.merchants.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMerchantRequest $request)
    {   
        $merchant = $request->validated();
        $merchant["location"] = json_encode(["lat" => $request["latitude"],"lng" => $request["longitude"]]);
        Merchant::create($merchant);
        
        return redirect()->route("dashboard.merchants.index");
    }

    /**
     * Display the specified resource.
     */
    public function show(Merchant $merchant)
    {
        $merchant = Merchant::find($merchant->id);
        return view("dashboard.merchants.show",compact('merchant'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Merchant $merchant)
    {
        $merchant = Merchant::find($merchant->id);
        return view("dashboard.merchants.edit",compact('merchant'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMerchantRequest $request, Merchant $merchant)
    {
        $validated = $request->validated();
        $validated["location"] = json_encode(["lat" => $request["latitude"],"lng" => $request["longitude"]]);
        Merchant::where("id",$merchant->id)->update($validated);
        
        return redirect()->route("dashboard.merchants.index");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Merchant $merchant)
    {
        $merchant =  Merchant::find($merchant->id);
        $merchant->delete();
        return redirect()->back();
    }
}
