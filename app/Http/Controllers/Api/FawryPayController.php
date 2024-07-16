<?php

namespace App\Http\Controllers\Api;

use App\Models\Card;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Cache;

class FawryPayController extends \App\Http\Controllers\Controller
{
        /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth:api', ['except' => ['']]);
    // }
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
        $merchantCode = env('FAWRY_MERCHANT_CODE');
        $merchantRefNum = rand(1000,9999);
        $merchant_sec_key =  env('FAWRY_SECURITY_CODE'); // For the sake of demonstration
        $amount = "1.00";
        $signature = hash('sha256' ,  $merchantCode . $merchantRefNum . $payment_method . $amount . $request->cardNumber . $request->year . $request->month . $request->cvv . $merchant_sec_key);
        
        // Send Request
        $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept'       => 'application/json'
            ])->post($endpoint,
            [
                'merchantCode' => $merchantCode,
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
        // $endpoint = "https://www.atfawry.com/ECommerceWeb/Fawry/payments/refund"; // live
        $endpoint = "https://atfawry.fawrystaging.com/ECommerceWeb/Fawry/payments/refund"; // Staging
        $reason = $request->reason ??  "refund user with amount for some app policies";
        $signature = $signature = hash('sha256' , $this->merchantCode . $this->merchantRefNum . $request->amount . $reason . $this->merchant_sec_key);
        
        $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept'       => 'application/json'
            ])
            ->post($endpoint,
            [
                'merchantCode' => $this->merchantCode,
                'merchantRefNum' => $this->merchantRefNum,
                'refundAmount' => $request->amount,
                'reason' => $reason,
                'signature' => $signature
            ]);
    }
}