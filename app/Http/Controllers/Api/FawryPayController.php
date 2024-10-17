<?php

namespace App\Http\Controllers\Api;

use App\Models\Card;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class FawryPayController extends Controller
{
    private $merchantCode;
    private $securityKey;
        /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['fawryIframeReturn','fawryNotification']]);
        $this->merchantCode = env('FAWRY_MERCHANT_CODE');
        $this->securityKey = env('FAWRY_SECURITY_CODE');
    }
    protected $errorCodes = [
            9901 => "merchant code is blank or invalid",
            9938 => "Order not found",
            9946 => "Blank or invalid signature",
            9935 => "Refunded amount greater than paid amount",
            9954 => "Order is not paid",
        ];
    
    /*
    * Authenticate card before adding it to user data
    * @return Response
    */
    public function authCard(Request $request){
        // Prepare Data
        // $endpoint = "https://www.atfawry.com/ECommerceWeb/Fawry/payments/charge"; // live
        $endpoint = "https://atfawry.fawrystaging.com/ECommerceWeb/Fawry/payments/charge"; // Staging
        $user = Auth::guard('api')->getuser();
        $payment_method = 'CARD';
        $merchantRefNum = Payment::latest()->first()->id + 1;
        $merchant_sec_key =  env('FAWRY_SECURITY_CODE'); // For the sake of demonstration
        $amount = "1.00";
        $signature = hash('sha256' ,  $this->merchantCode . $merchantRefNum . $payment_method . $amount . $request->cardNumber . $request->year . $request->month . $request->cvv . $merchant_sec_key);
        
        // Send Request
        $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept'       => 'application/json'
            ])->post($endpoint,
            [
                'merchantCode' => $this->merchantCode,
                'merchantRefNum' => $merchantRefNum,
                'customerMobile' => $user->phone,
                'customerEmail' => $user->email,
                'cardNumber' => $request->cardNumber,
                'cardExpiryYear' => $request->year,
                'cardExpiryMonth' => $request->month,
                'cvv' => $request->cvv,
                'amount' => $amount,
                'currencyCode' => 'EGP',
                'language' => 'en-gb',
                'chargeItems' => [
                      'itemId' => '1',
                      'description' => 'catch amount to verify user card',
                      'price' => '1',
                      'quantity' => '1'
                                  ],
                'signature' => $signature,
                'paymentMethod' => 'CARD',
                'description' => 'catch amount to verify user card'
            ]);
            return $response->body();
        
    }
    
    /*
    * refund some amount to user
    * @return Response
    */
    public function refund(Request $request){
      $request->validate([
      	"paymentId" => "required",
        "amount" => "required"
      ]);
        // $endpoint = "https://www.atfawry.com/ECommerceWeb/Fawry/payments/refund"; // live
        $endpoint = "https://atfawry.fawrystaging.com/ECommerceWeb/Fawry/payments/refund"; // Staging
        $reason = $request->reason ??  "";
        $signature = $signature = hash('sha256' , $this->merchantCode . $request->paymentId . $request->amount . $reason . $this->securityKey);
        
        $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept'       => 'application/json'
            ])
            ->post($endpoint,
            [
                'merchantCode' => $this->merchantCode,
                'merchantRefNum' => $request->paymentId,
                'refundAmount' => $request->amount,
                'reason' => $reason,
                'signature' => $signature
            ]);
      	return $response->body();

    }

    // Get Iframe Url
    public function getIframeUrl(){
        $responseUrl = "https://dev.powerfull-eg.com/payment/response";
        $user = Auth::guard('api')->user();
        $merchantCode = env('FAWRY_MERCHANT_CODE');
        $returnUrl = route('website.fawry-payment-response');
        $endpoint = "https://atfawry.fawrystaging.com/atfawry/plugin/card-token?accNo=$merchantCode&customerProfileId=$user->id&returnUrl=$returnUrl";
        return $endpoint;
    }

    // Fawry notifcation url
    public function fawryNotification(Request $request){
      	$request_data = json_encode($request->all());
        DB::table('test')->insert(["request" => "inside fawry notifcation with body: " . $request_data]);
    }

    // fawry Iframe Return
    public function fawryIframeReturn(Request $request){
        /*
        'statusCode':	200
        'statusDescription':	Operation done successfully
        'isDefault':	false
        'token':	822faaffd167d0d56e327153916d228fa44814d6d103a923fcc2ea81b4429bce
        'creationDate':	1728399474035
        'lastFourDigits':	4242
        'firstSixDigits':	424242
        'default':	false
        'cardHolderName':	'John Doe'
        */
      $request_data = json_encode($request->all());
      DB::table('test')->insert(["request" => "inside fawry iframe url return with body: " . $request_data]);
        
    }

    // Pay with saved token 
    public function payWithSavedToken(Request $request){
      	$endpoint = "https://atfawry.fawrystaging.com/ECommerceWeb/Fawry/payments/charge"; // Staging
        $user = Auth::guard('api')->getuser();
        $payment_method = 'CARD';
        $merchantRefNum = Payment::latest()->first()->id + 6;
        $merchant_sec_key =  env('FAWRY_SECURITY_CODE'); // For the sake of demonstration
        $amount = $request->amount;
      	$returnUrl = url('api/fawry-notification');
      // merchantCode + merchantRefNum + customerProfileId (if exists, otherwise "") + paymentMethod + amount (in two decimal format 10.00) + cardToken + cvv + returnUrl + secureKey
        $signature = hash('sha256' ,  $this->merchantCode . $merchantRefNum . $user->id .$payment_method . $amount . $request->cardToken . $request->cvv. $returnUrl . $merchant_sec_key);
        
        // Send Request
        $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept'       => 'application/json'
            ])->post($endpoint,
            [
                'merchantCode' => $this->merchantCode,
                'merchantRefNum' => $merchantRefNum,
                'customerName' => $user->first_name . ' ' . $user->last_name,
                'customerMobile' => $user->phone,
                'customerEmail' => $user->email,
              	'customerProfileId' => $user->id,
              	'cardToken' => $request->cardToken,
                'cvv' => $request->cvv,
                'amount' => $amount,
                'currencyCode' => 'EGP',
                'language' => 'en-gb',
              	'returnUrl' => $returnUrl,
                'chargeItems' => [
                  [
                      'itemId' => '1',
                      'description' => 'catch amount to verify user card',
                      'price' => $amount,
                      'quantity' => '1'
                  ],               
                ],
                'signature' => $signature,
                'paymentMethod' => $payment_method,
                'description' => 'deduct amount to of using our item',
            	'enable3DS' => true
            ]);
            return $response->body();
        

    }

    // Get User Saved Tokens
    public function getUserSavedTokens(){
        $user = Auth::guard('api')->user();
        $signature = hash('sha256' , $this->merchantCode . $user->id . $this->securityKey);
        // Staging url
        $url = "https://atfawry.fawrystaging.com/ECommerceWeb/Fawry/cards/cardToken?merchantCode=$this->merchantCode&customerProfileId=$user->id&signature=$signature";
        $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept'       => 'application/json'
            ])->get($url);
            return $response->body();
    }
  
    // Delete User Saved Token
    public function deleteUserSavedToken(Request $request){
        $request->validate([
            'cardToken' => 'required|string'
        ]);

        $user = Auth::guard('api')->user();
        $signature = hash('sha256' , $this->merchantCode . $user->id . $request->cardToken . $this->securityKey);
        
        // Staging url
        $url = "https://atfawry.fawrystaging.com/ECommerceWeb/Fawry/cards/cardToken?merchantCode=$this->merchantCode&customerProfileId=$user->id&cardToken=$request->cardToken&signature=$signature";
        $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept'       => 'application/json'
            ])->delete($url);
            return $response->body();
    }
  
  // Get Payment Data
  public function getPaymentData(Request $request){
     	$signature = hash('sha256' , $this->merchantCode . $request->paymentId . $this->securityKey);
        // Staging url
        $url = "https://atfawry.fawrystaging.com/ECommerceWeb/Fawry/payments/status/v2?merchantCode=$this->merchantCode&merchantRefNumber=$request->paymentId&signature=$signature";
        $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept'       => 'application/json'
            ])->get($url);
    return $response->body();
  }
  
    // Make Payment with Reference Number => for Integration proccess
    public function createRefrenceNumber(Request $request){
    $request->validate([
        'amount' => 'required|numeric',
        'paymentId' => 'required|numeric'
    ]);
    $user = Auth::guard('api')->user();
    $paymentMethod = "PayAtFawry";
    $signature = hash('sha256' , $this->merchantCode . $request->paymentId . $user->id . $paymentMethod . $request->amount . $this->securityKey);
    
    $url = "https://atfawry.fawrystaging.com/ECommerceWeb/Fawry/payments/charge";
    $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept'       => 'application/json'
        ])->post($url,
        [
            'merchantCode' => $this->merchantCode,
            'merchantRefNum' => $request->paymentId,
            'customerProfileId' => $user->id,
            'customerName' => $user->first_name . ' ' . $user->last_name,
            'customerMobile' => "+20" . $user->phone,
            'customerEmail' => $user->email ?? 'dummy@dummy.com',
            'amount' => $request->amount,
            'description' => 'deduct amount to of using our item',
            'orderWebHookUrl' => url('api/fawry-notification'),
            'language' => 'en-gb',
            'paymentExpiry' => 1728620316000,
            'chargeItems' => [
            [
                'itemId' => '1',
                'description' => 'catch amount to verify user card',
                'price' => $request->amount,
                'quantity' => '1'
            ],               
            ],
            'signature' => $signature,
            'paymentMethod' => $paymentMethod,
        ]);
      return $response->body();
  }
  
  // Make Payment with Qrcode => for Integration proccess
  public function createQrCode(Request $request){
    $request->validate([
        'amount' => 'required|numeric',
        'paymentId' => 'required|numeric'
    ]);
    $user = Auth::guard('api')->user();
    $paymentMethod = "MWALLET";
    $signature = hash('sha256' , $this->merchantCode . $request->paymentId . $user->id . $paymentMethod . $request->amount . $this->securityKey);
    
    $url = "https://atfawry.fawrystaging.com/ECommerceWeb/Fawry/payments/charge";
    $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept'       => 'application/json'
        ])->post($url,
        [
            'merchantCode' => $this->merchantCode,
            'merchantRefNum' => $request->paymentId,
            'customerProfileId' => $user->id,
            'customerName' => $user->first_name . ' ' . $user->last_name,
            'customerMobile' => "+20" . $user->phone,
            'customerEmail' => $user->email ?? 'dummy@dummy.com',
            'amount' => $request->amount,
            'description' => 'deduct amount to of using our item',
            'orderWebHookUrl' => url('api/fawry-notification'),
            'currencyCode' => 'EGP',
            'language' => 'en-gb',
            'chargeItems' => [
            [
                'itemId' => '1',
                'description' => 'catch amount to verify user card',
                'price' => $request->amount,
                'quantity' => '1'
            ],               
            ],
            'signature' => $signature,
            'paymentMethod' => $paymentMethod,
        ]);
      return $response->body();
  }
}