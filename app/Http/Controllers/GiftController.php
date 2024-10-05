<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use Illuminate\Http\Request;
use App\Models\Gift;
use App\Models\GiftUser;
use \App\Models\User;
use \App\Models\Operation;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\BajieController;
use Illuminate\Support\Facades\DB;

class GiftController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['']]);
    }
    // Get All Gifts related to user
    public function index() {
        $user = Auth::guard('api')->getuser();
        $gifts = GiftUser::with('gift')->where("user_id",$user["id"])->get();

        return response($gifts ?? [])->withHeaders(['Access-Control-Allow-Origin', '*']);
    }
    
    // add the all added gifts
    public function addAndCheckGifts($device=null){
        return $this->firstOrderGift($device);
    }
    
    // the default gifts
    private function firstOrderGift($device=null){
        $user = Auth::guard('api')->getuser();
        $device = \App\Models\Device::where('device_id',$device);

        $shop = Shop::find($device->first()->shop_id);
        DB::table('test')->insert(['request' => json_encode($shop->first())]);
        // $shop = $this->getShopData($device);
        if(Operation::where("user_id",$user["id"])->count() != 1) return null;
        $code = Str::random(6);
        $gift = GiftUser::create([
            "gift_id" => 1,
            "user_id" => $user["id"],
            "code" => $code,
            "expire" => now()->add(7, 'day'),
            "shop_id" => $shop ? $shop->id : null,
            "shop_name" => $shop? $shop->name : null
        ]);
        $gift = GiftUser::find($gift->id);
        $gift->push($gift->gift);

        // Send SMS to shop
        $message = "تم إضافة هديه جديده : " . $code;
        $sms = new SMSController();
        $smsRequest = new Request();
        $smsRequest->merge(["mobile" => $shop->phone, "message" => $message, "language" => 2]);
        $shop->phone ? $sms->store($smsRequest) : '';
        
        return $gift;
    }
    
    // Get the shop data for use in gifts
    private function getShopData($device=null){
        $shops = new BajieController();
        $shops = $shops->getDevices();

        foreach(json_decode($shops->content(),true) as $shop){
            if($shop["DeviceId"] == $device)
                return $shop;
        }
        return null;
    }

}
