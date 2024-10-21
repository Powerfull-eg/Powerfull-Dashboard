<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Api\BajieController;
use App\Models\Device;
use App\Http\Requests\StoreDeviceRequest;
use App\Http\Requests\UpdateDeviceRequest;
use App\Models\Provider;
use App\Models\Shop;
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
        $shops = Shop::pluck('name', 'id');
        $providers = Provider::pluck('name', 'id');
        return view("dashboard.devices.create", compact('shops','providers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'shop_id' => 'required|exists:shops,id|unique:devices,shop_id',
            'provider_id' => 'required|exists:providers,id',
            'device_id' => 'required|string',
            'status' => 'required',
            'slots' => 'required|integer',
        ]);

        $device = Device::create([
            'shop_id' => $request->shop_id,
            'provider_id' => $request->provider_id,
            'device_id' => $request->device_id,
            'status' => $request->status,
            'slots' => $request->slots,
        ]);

        return redirect()->route('dashboard.devices.index')->with('success', __('Device created successfully.'));
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
