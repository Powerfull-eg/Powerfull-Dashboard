<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BajieController;
use App\Models\Device;
use App\Http\Requests\StoreDeviceRequest;
use App\Http\Requests\UpdateDeviceRequest;
use App\Models\Provider;
use App\Models\Shop;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DeviceController extends Controller
{
    public function getDevices(Request $request) {
        $devices = Device::with('provider')->get();
        $data = [];
        foreach($devices as $device) {
            $provider = new $device->provider->controller;
            $providerData = $provider->getDeviceData($device->device_id);
            if($providerData) {
                $data[$device->device_id] = $providerData;
            }
        }
        return response()->json($data);
    }
}