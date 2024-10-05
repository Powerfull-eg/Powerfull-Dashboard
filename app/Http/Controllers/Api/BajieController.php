<?php

namespace App\Http\Controllers\Api;

use App\Events\OrderPlaced;
use App\Models\Operation;
use App\Models\VoucherOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Sleep;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\GiftController;
class BajieController extends \App\Http\Controllers\Controller
{
        public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['test',"getShops","getPrice","getDevices","updateRentData"]]);
    }
    
    public function test(Request $request){
        $users = DB::table('test')->insert(["request" => json_encode($request->all())]);
    }
    
    public function rentStatus() 
    {
        return response()->json((["status" => 1]));
    }
    
    // Rent powerbank api 
    public function rentPowerbank(Request $request){
        $callbackUrl = action([BajieController::class, 'updateRentData']);
        $deviceId = $request->device;
        $dataUrl = "https://developer.chargenow.top/cdb-open-api/v1/rent/order/create?callbackURL=$callbackUrl&deviceId=$deviceId";
        


        $response = Http::withBasicAuth(env("BAJIE_API_USERNAME"), env("BAJIE_API_PASSWORD"))->post($dataUrl);
        $responseBody = json_decode($response->body(),true);
        if($response->status() == 200 && $responseBody['code'] == 0 ){

            $operation = Operation::create([
                "station_id" => $deviceId,
                "tradeNo" => $responseBody["data"]["tradeNo"],
                "user_id" =>  $request->userId,
                "card_id" => $request->card["id"],
            ]);
            
            // Add and check for gifts
            $gift = new GiftController();
            $gift = $gift->addAndCheckGifts($deviceId);
            
            // add record for voucher if exist
            if($request->voucher && $request->voucher != "null") {
                VoucherOrder::create([
                    "user_id" => $request->userId,
                    "voucher_id" => $request->voucher,
                    "order_id" => $operation->id
                ]);
            }
        }
        
        return response()->json([$response->status(),$response->body(),"gift" => $gift]);
    }
    
    // update Renting data
    public function updateRentData(Request $request){
        Sleep::for(2)->seconds();
         
        $url = "https://developer.chargenow.top/cdb-open-api/v1/rent/order/detail?tradeNo=$request->tradeNo";
        $response = Http::withBasicAuth(env("BAJIE_API_USERNAME"), env("BAJIE_API_PASSWORD"))->get($url);
        $responseBody =json_decode($response->body(),true);
        
        // Handle Rent & Return operation
        if($responseBody["code"] == 0){
                Operation::where("tradeNo",$responseBody["data"]["orderId"])->update([
                    "powerbank_id" => $responseBody["data"]["batteryId"],
                    "borrowSlot"   => $responseBody["data"]["borrowSlot"],
                    "borrowTime"   => $responseBody["data"]["borrowTime"],
                    "returnTime"   => $responseBody["data"]["returnTime"] ?? null,
                    "returnShop"   => $responseBody["data"]["returnShop"] ?? null,
                    "status"   => $responseBody["data"]["borrowStatus"] == 3 ? 2 : 1,
                ]);

        }
        // Handle Return operation
        return response()->json([$response->status(),$response->body()]);
    }
    
    public function updateRentManually(Request $request){
        $url = "https://developer.chargenow.top/cdb-open-api/v1/rent/order/detail?tradeNo=$request->tradeNo";
        $response = Http::withBasicAuth(env("BAJIE_API_USERNAME"), env("BAJIE_API_PASSWORD"))->get($url);
        $responseBody =json_decode($response->body(),true);
        if($responseBody["code"] == 0){
            Operation::where("tradeNo",$responseBody["data"]["orderId"])->update([
                    "powerbank_id" => $responseBody["data"]["batteryId"],
                    "borrowSlot"   => $responseBody["data"]["borrowSlot"],
                    "borrowTime"   => $responseBody["data"]["borrowTime"],
                    "returnTime"   => $responseBody["data"]["returnTime"] ?? null,
                    "returnShop"   => $responseBody["data"]["returnShop"] ?? null,
                    "status"   => $responseBody["data"]["borrowStatus"] == 3 ? 2 : 1,
                ]);
         }
         
        return response()->json([$response->status(),$response->body()]);
    }
    
    // Get All Devices Api
    public function getDevices(){
        $deviceListUrl = "https://developer.chargenow.top/cdb-open-api/v1/cabinet/getAllDevice";
        $deviceList = Http::withBasicAuth(env("BAJIE_API_USERNAME"), env("BAJIE_API_PASSWORD"))->get($deviceListUrl);
        
        
        $devicesInfo= array();
        foreach(json_decode($deviceList,true)["data"] as $deviceData){
            $device = Http::withBasicAuth(env("BAJIE_API_USERNAME"), env("BAJIE_API_PASSWORD"))->get("https://developer.chargenow.top/cdb-open-api/v1/rent/cabinet/query?deviceId=".$deviceData["pCabinetid"]);
            $res = json_decode($device->body(),true);
            if($device->status() == 200){
                if($res["code"] == 2004){
                    $devicesInfo[] = [
                        "DeviceId" => $deviceData["pCabinetid"],
                        "shopId" => $deviceData["pShopid"],
                        "statusCode" => 2004,
                        "data" => "Device Not Online",
                    ];
                }else if($res["code"] == 0){
                    $devicesInfo[] = [
                        "DeviceId" => $deviceData["pCabinetid"],
                        "shopId" => $deviceData["pShopid"],
                        "statusCode" => 0,
                        "data" => $res["data"],
                    ];
                }else {
                    $devicesInfo[] = [
                        "DeviceId" => $deviceData["pCabinetid"],
                        "shopId" => $deviceData["pShopid"],
                        "statusCode" => $res["code"]
                    ];
                }
            }else{
                $devicesInfo[] = [
                    "DeviceId" => $deviceData["pCabinetid"],
                    "shopId" => $deviceData["pShopid"],
                    "statusCode" => $device->status(),
                    "data" => $device->msg()
                ];
            }  
            
        }

        return response()->json($devicesInfo);
    }

    // Get All Shops Api 
    public function getShops(){
        $apiUrl = "https://developer.chargenow.top/cdb-open-api/v1/shop/getShopList";
        
        $shops = Http::withBasicAuth(env("BAJIE_API_USERNAME"), env("BAJIE_API_PASSWORD"))->get($apiUrl);

        return response()->json([$shops->status(),$shops->body()]);
    }


    // Check Device return and Update operation 
    public function checkReturn(Request $request,string $id){
        
        $operation = Operation::find($id);

        $dataUrl = "https://developer.chargenow.top/cdb-open-api/v1/rent/order/detail?tradeNo=".$operation->tradeNo;

        $response = Http::withBasicAuth(env("BAJIE_API_USERNAME"), env("BAJIE_API_PASSWORD"))->get($dataUrl);
        
        $responseBody = json_decode($response->body(),true);
        
        if($response->status() == 200 && $responseBody['code'] == 0 && $responseBody["data"]["borrowStatus"] == 3){
            $operation->update([
                "powerbank_id" => $responseBody["data"]["batteryId"],
                "borrowTime" => $responseBody["data"]["borrowTime"],
                "returnTime" => $responseBody["data"]["returnTime"],
                "borrowSlot" => $responseBody["data"]["borrowSlot"],
                "returnShop"   => $responseBody["data"]["returnShop"],
                "status" => 2
            ]);
        }
        
        return response()->json(["order" => $operation]);
    }

    public static function getDeviceData($device_id){
        $response = Http::withBasicAuth(env("BAJIE_API_USERNAME"), env("BAJIE_API_PASSWORD"))->get("https://developer.chargenow.top/cdb-open-api/v1/rent/cabinet/query?deviceId=$device_id");
        if($response->status() == 20 && json_decode($response->body(),true)["code"] == 0){
            return json_decode($response->body(),true)["data"];
        }
    }
}
