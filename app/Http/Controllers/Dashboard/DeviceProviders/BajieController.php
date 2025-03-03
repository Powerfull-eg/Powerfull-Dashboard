<?php 

namespace App\Http\Controllers\Dashboard\DeviceProviders;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\Device;
use App\Models\Operation;
use App\Models\VoucherOrder;
use Error;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BajieController extends Controller
{
    public function getShops(){
        $url = "https://developer.chargenow.top/cdb-open-api/v1/shop/getShopList";
        
        $shops = Http::withBasicAuth(env("BAJIE_API_USERNAME"), env("BAJIE_API_PASSWORD"))->get($url);
        $shops = collect(json_decode($shops->body(),true)['data']);
        $shops = $shops->map(function($shop){
            $data = [
                "name" => $shop['shopName'],
                "provider_id" => $shop['newID'],
                "logo" => $shop['shopBanner'] ?? null,
                "icon" => $shop['shopIcon'] ?? null,
                "governorate" => 'Cairo',
                "city" => 'Cairo',
                "address" => $shop['shopAddress'] ?? null,
                "location_latitude" => $shop['latitude'],
                "location_longitude" => $shop['longitude'],
            ];

            return $data;
        });

        return $shops;
    }

    // Get Device By shop Id 
    public function getDeviceByShopId(string $shop){
        $url = "https://developer.chargenow.top/cdb-open-api/v1/cabinet/getDeviceByShopId";
        
        $device = Http::withBasicAuth(env("BAJIE_API_USERNAME"), env("BAJIE_API_PASSWORD"))->withQueryParameters(["shopId" => $shop])->get($url);
        $device = collect(json_decode($device->body(),true));

        return $device;
    }

    /*
    * Get Device's Powerbanks Data
    * @param string $device
    * @return array
    */
    public function getDevicePowerbanks(string $device){
        $url = "https://developer.chargenow.top/cdb-open-api/v1/cabinet/batteryListByCabinetId/$device";
        $response = Http::withBasicAuth(env("BAJIE_API_USERNAME"), env("BAJIE_API_PASSWORD"))->get($url);
        $data = json_decode($response->body(),true)['data'] ?? null;
        if(!$data || count($data) == 0) return [];
        
        // Handle Returned data
        $slots = [];
        foreach($data as $index => $slot){
            $slots[$index] = [
                "Battery_id" => $slot['pbatteryid'],
                "Slot_Num" => $slot['pkakou'],
                "Battery_Exist" => strlen($slot['pBatteryid']) > 0 ? true : false,
                "Cabinet_id" => $slot['pcabinetid'],
                "Erorr_Number" => $slot['pFaultType'],
                "Error" => $slot['pFaultCause'],
                "Guard" => $slot['pIsGuard'],
            ];
        }
        return $slots; 
    }

    /*
    * Get Device By Device ID
    * @param string $device
    * @return array
    */
    public function getDeviceById(string $device){
        $url = "https://developer.chargenow.top/cdb-open-api/v1/rent/cabinet/query";
        $response = Http::withBasicAuth(env("BAJIE_API_USERNAME"), env("BAJIE_API_PASSWORD"))->withQueryParameters(["deviceId" => $device])->get($url);
        $response = json_decode($response->body(),true);

        return $response['data'] ?? $response;
    }
    
    /*
    * Get Device data to fixed functions name convention
    * @param string $device
    * @return array
    */
    public function getDeviceData(string $device){
      return $this->getDeviceById($device);
    }

    /*
    * Eject Powerbank
    * @param string $device
    * @param string $slotNum
    * @return array
    */
    public function ejectPowerbank(string $device, string $slotNum){
        $url = "https://developer.chargenow.top/cdb-open-api/v1/cabinet/ejectByRepair";
        $response = Http::withBasicAuth(env("BAJIE_API_USERNAME"), env("BAJIE_API_PASSWORD"))->withQueryParameters(["cabinetid" => $device, "slotNum" => $slotNum])->post($url);
        $response = json_decode($response->body(),true);
        return $response;
    }

    /*
    * Device Operation
    * @param string $device
    * @param string $operation
    * @param int $slotNum
    * @return array
    */

    public function deviceOperation(string $device, string $operation,int $slotNum = 1) {
        $operations = ["restart","pop","popall","popallForNoAuth","popallForAuth","heartbeat","lock","unlock","lockStopCharge","report"];
        
        if(!in_array($operation,$operations)){
            throw new Error("Operation $operation not exist in operations list.");
        }

        $url = "https://developer.chargenow.top/cdb-open-api/v1/cabinet/operation";
        $response = Http::withBasicAuth(env("BAJIE_API_USERNAME"), env("BAJIE_API_PASSWORD"))->withQueryParameters(["cabinetid" => $device, "operationType" => $operation,"slotNum" => $slotNum,"reason" => "Management Control"])->post($url);
        $response = json_decode($response->body(),true);
        return $response;
    }

    /*
    * Slots Info
    * @param string $device
    * @return array
    */
    public function getSlotsInfo(string $device) : array
    {
        $url = "https://developer.chargenow.top/cdb-open-api/v1/cabinet/slotByCabinetId/{$device}";
        $response = Http::withBasicAuth(env("BAJIE_API_USERNAME"), env("BAJIE_API_PASSWORD"))->get($url);
        $data = json_decode($response->body(),true)['data'] ?? null;
        if(!$data || count($data) == 0) return [];
        
        // Handle Returned data
        $slots = [];
        foreach($data as $index => $slot){
            $slots[$index] = [
                "Battery_id" => $slot['pbatteryid'],
                "Slot_Num" => $slot['pkakou'],
                "Battery_Exist" => strlen($slot['pBatteryid']) > 0 ? true : false,
                "Cabinet_id" => $slot['pcabinetid'],
                "Erorr_Number" => $slot['pFaultType'],
                "Error" => $slot['pFaultCause'],
                "Guard" => $slot['pIsGuard'],
            ];
        }
        return $slots;
    }
    
}