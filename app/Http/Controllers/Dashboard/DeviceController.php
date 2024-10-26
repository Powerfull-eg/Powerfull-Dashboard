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
        $validated = $request->validate([
            'shop_id' => 'required|exists:shops,id|unique:devices,shop_id',
            'provider_id' => 'required|exists:providers,id',
            'device_id' => 'required|string|unique:devices,device_id',
            'status' => 'required',
            'slots' => 'required|integer',
            'sim_number' => 'required|max:25'
        ]);

        $validated["created_by"] = auth()->user()->id;
        $validated["updated_by"] = auth()->user()->id;

        $device = Device::create($validated);

        return redirect()->route('dashboard.devices.index')->with('success', __('Device created successfully.'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Device $device)
    {
        return view("dashboard.devices.show", compact('device'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Device $device)
    {
        $shops = Shop::pluck('name', 'id');
        $providers = Provider::pluck('name', 'id');
        return view("dashboard.devices.edit", compact('shops','providers','device'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Device $device)
    {
        $validated = $request->validate([
            'shop_id' => 'required|exists:shops,id|unique:devices,shop_id,'.$device->id,
            'provider_id' => 'required|exists:providers,id',
            'device_id' => 'required|string|unique:devices,device_id,'.$device->id,
            'status' => 'required',
            'slots' => 'required|integer',
            'sim_number' => 'required|max:25'
        ]);
        
        $validated["updated_by"] = auth()->user()->id;

        $device->update($validated);

        return redirect()->back()->with('success', __('Device updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Device $device)
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

    /*
    * Device Operation
    * @param string $device
    * @param string $operation
    * @param int $slotNum
    * @return array
    */
    public function deviceOperation(Request $request) {
        $request->validate([
            'device' => "required|string|exists:devices,device_id",
            'operation' => "required|string",
            'slotNum' => "numeric"
        ]);
        
        $device = Device::where('device_id',$request->device)->with('provider')->first();
      	$controller = new $device->provider->controller;
        $data = $controller->deviceOperation($request->device,$request->operation,$request->slotNum);
        
        return $data;
    }

    /*
    * Eject single Powerbank for repair
    */

    public function ejectPowerbank(Request $request) {
        $request->validate([
            'device' => "required|string|exists:devices,device_id",
            'slotNum' => "required|numeric"
        ]);
        
        $device = Device::where('device_id',$request->device)->with('provider')->first();
      	$controller = new $device->provider->controller;
        $data = $controller->ejectPowerbank($request->device,$request->slotNum);
        
        return $data;
    }
    
}