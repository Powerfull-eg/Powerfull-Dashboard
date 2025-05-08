<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\FawryPayController;
use App\Http\Controllers\Api\PaymobController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\Operation;
use App\Models\Setting;

class PaymentController extends Controller
{
    public $gatways = [
        'fawry' => FawryPayController::class,
        'paymob' => PaymobController::class
    ];
    
    public $defaultGatway;

    public function __construct(){
        $this->defaultGatway = Setting::where("key","payment_gateway")->first()->value;
    }

    // get iframe url
    public function getIframeUrl(Request $request)
    {
        $user = Auth::guard('api')->user();
        $request->merge(["userId" => $user->id]);
        $gatway = $request->gateway ?? $this->defaultGatway;
        $controller = $this->gatways[$gatway];
        return (new $controller())->getIframeUrl($request);
    }

    // the callback function when payment done
    public function IframeCallback(Request $request){
        $gatway = $request->gateway ?? $this->defaultGatway;
        $controller = $this->gatways[$gatway];
        return (new $controller())->iframeCallback($request);
    }

    // Pay with saved token
    public function payWithSavedToken($order){
        $gatway = $this->getPaymentGateway($order->card_id) ?? $this->defaultGatway;
        $controller = $this->gatways[$gatway];
        return (new $controller())->payWithSavedToken($order);
    }

    // get Card default payment gateway 
    public function getPaymentGateway($card_id){
        $card = Card::find($card_id);
        return $card->gateway;
    }
}