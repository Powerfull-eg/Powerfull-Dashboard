<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\powerbanks;
use App\Http\Requests\StorepowerbanksRequest;
use App\Http\Requests\UpdatepowerbanksRequest;
use App\Models\Shop;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Request;

class PowerbanksController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $shops = Shop::all();
        return view('dashboard.powerbank.index',compact('shops'));
    }

    /**
     * Ejecting the targeted powerbank.
     */
    public function eject(Request $request)
    {
        $validated = $request->validate([
            'device_name' => 'required',
            'powerbank' => 'required',
        ]);

        $url = "https://developer.chargenow.top/cdb-open-api/v1/cabinet/ejectByRepair?cabinetid=". $validated['device_name'] ."&slotNum=".$validated['powerbank'];
        $response = Http::withBasicAuth(env("BAJIE_API_USERNAME"), env("BAJIE_API_PASSWORD"))->post($url);
        if($response->status() == 200){
            return redirect()->back()->with('success',__('PowerBank Ejected Successfully'));
        }
        return redirect()->back()->with('error',__('Failed to eject PowerBank'));
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
    public function store(StorepowerbanksRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(powerbanks $powerbanks)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(powerbanks $powerbanks)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatepowerbanksRequest $request, powerbanks $powerbanks)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(powerbanks $powerbanks)
    {
        //
    }
}