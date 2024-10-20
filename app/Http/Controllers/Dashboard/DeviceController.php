<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Api\BajieController;
use App\Models\Device;
use App\Http\Requests\StoreDeviceRequest;
use App\Http\Requests\UpdateDeviceRequest;
use App\Models\Provider;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $startDate = $request->startDate ?? null;
        $endDate = $request->endDate ?? null;
        $allDevices = Device::with('shop')->get();
        $devices = $startDate ? $allDevices->where("created_at",'>=',$startDate) : $allDevices;
        $devices = $endDate ? $devices->where("created_at",'<=',$endDate) : $devices;
        return view("dashboard.devices.index", compact('startDate','endDate','devices','allDevices'));
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
    public function store(StoreDeviceRequest $request)
    {
        //
    }

    /**
     * Get data of specified device.
     */
    public function getDeviceData(Request $request)
    {
        $device = Device::where('device_id',$request->deviceID)->with('provider')->first();
        $provider = new $device->provider->controller;
        $data = $provider->getDeviceData($request->deviceID);
        
        return $data;
    }

    /**
     * Display the specified resource.
     */
    public function show(Device $device)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Device $device)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDeviceRequest $request, Device $device)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Device $device)
    {
        //
    }
}
