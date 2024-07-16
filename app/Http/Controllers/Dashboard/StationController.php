<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Station;
use App\Http\Requests\StoreStationRequest;
use App\Http\Requests\UpdateStationRequest;
use App\Models\Merchant;

class StationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view("dashboard.stations.index");
    }   

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $merchants = Merchant::pluck("name","id");
        return view("dashboard.stations.create",compact("merchants"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStationRequest $request)
    {
        $station = $request->validated();
        Station::create($station);
        
        return redirect()->route("dashboard.stations.index");
    }

    /**
     * Display the specified resource.
     */
    public function show(Station $station)
    {
        $station = Station::findOrFail($station);
        return view("dashboard.stations.show",compact('station'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Station $station)
    {
        $station = Station::find($station->id);
        $merchants = Merchant::pluck("name","id");

        return view("dashboard.stations.edit",compact('station','merchants'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStationRequest $request, Station $station)
    {       
        $vaildated = $request->validated();
        Station::where("id",$station->id)->update($vaildated);
        
        return redirect()->route("dashboard.stations.index");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Station $station)
    {

        $station =  Station::find($station->id);
        $station->delete();
        return redirect()->back();
    }
}
