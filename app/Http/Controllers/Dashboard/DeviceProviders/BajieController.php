<?php 

namespace App\Http\Controllers\Dashboard\DeviceProviders;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\Device;
use App\Models\Operation;
use App\Models\VoucherOrder;
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
        dd($shops);
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
        $response = json_decode($response->body(),true)['data'];

        return $response;   
    }

    /*
    * Get Device By Device ID
    * @param string $device
    * @return array
    */
    public function getDeviceById(string $device){
        $url = "https://developer.chargenow.top/cdb-open-api/v1/rent/cabinet/query";
        $response = Http::withBasicAuth(env("BAJIE_API_USERNAME"), env("BAJIE_API_PASSWORD"))->withQueryParameters(["deviceId" => $device])->get($url);
        $response = json_decode($response->body(),true)['data'];

        return $response;
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

    
}