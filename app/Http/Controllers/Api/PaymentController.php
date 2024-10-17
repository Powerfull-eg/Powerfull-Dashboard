<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\FawryPayController;
use App\Http\Controllers\Api\PaymobController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class PaymentController extends Controller
{
    public $gatways = [
        'fawry' => FawryPayController::class,
        'paymob' => PaymobController::class
    ];
    
    public $defaultGatway = 'paymob';

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
}