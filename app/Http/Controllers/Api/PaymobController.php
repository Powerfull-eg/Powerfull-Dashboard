<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use App\Models\Card;
use App\Models\Payment;
use App\Models\Operation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Sleep;

class PaymobController extends \App\Http\Controllers\Controller
{
    public $authKey;

    // Get the proccess authentication key
    public function getAuthKey(){
        $url = "https://accept.paymobsolutions.com/api/auth/tokens";
        $response = HTTP::post($url,["api_key"=> env("PAYMOB_API_KEY") ]);
        $responseBody = json_decode($response->body(),true);
        $this->authKey  = $responseBody["token"] ?? null;
        return $responseBody["token"] ?? null;
    }
    
    // Register the order and get order_id to proccess the operation
    public function registerOrder(Request $request){
        $token = $this->getAuthKey();
        if(!$token) return null;
        $url = "https://accept.paymobsolutions.com/api/ecommerce/orders";
        $response = HTTP::post($url,[
              "auth_token"=> "$token",
              "delivery_needed" => "false",
              "amount_cents" => ($request->amount ?? 1 * 100),
              "currency" => "EGP"
        ]);

        $responseBody = json_decode($response->body(),true);
        return $responseBody["id"] ? [$responseBody["id"],$token]: null;
    }
    
    // get card payment key for moto pay
    public function getCardPaymentKey(Request $request,$moto = true){
        $registeredOrder = $this->registerOrder($request);
        $orderId = $registeredOrder[0] ?? null;
        if(!$orderId) return null;
        $user = User::find($request->userId);
        $integrationId = $moto ? env("PAYMOB_MOTO_INTEGRATION_ID") : env("PAYMOB_INTEGRATION_ID");
        $url = "https://accept.paymobsolutions.com/api/acceptance/payment_keys";
        $response = HTTP::post($url,[
              "auth_token" => $registeredOrder[1],
              "amount_cents" => ($request->amount * 100),
              "expiration" => 3600, 
              "order_id" => $orderId,
              "billing_data"=> [
                "apartment"=> "001", 
                "email"=> $user->email, 
                "floor"=> "01", 
                "first_name"=> $user->first_name, 
                "street"=> "Tahrir ST", 
                "building"=> "0001",
                "phone_number"=> ("+20" . $user->phone), 
                "shipping_method"=> "none", 
                "postal_code"=> "11828", 
                "city"=> "Cairo",
                "country"=> "EG", 
                "last_name"=> $user->last_name, 
                "state"=> "Cairo"
              ], 
              "currency" => "EGP",
              "integration_id" => $integrationId,
              "lock_order_when_paid" => "false"
        ]);
        $responseBody = json_decode($response->body(),true);
        // DB::table("test")->insert(["request" => $response->body()]);

        if(isset($responseBody["token"])){
            Payment::create([
                "user_id" => $user->id,
                "payment_order_id" => $orderId,
                "token" => $this->authKey
                ]);
        }
        return $responseBody["token"] ?? null;
    }
    
    // Void a transaction (Same day)
    public function voidPayment(Request $request, $tries = 0)
    {
        $url = "https://accept.paymob.com/api/acceptance/void_refund/void?token=". $request->authToken;
        // Sleep::for(10)->second();
        try{
            $response = HTTP::post($url, ["transaction_id" => $request->transactionID ]);
            DB::table('test')->insert(["request" => "voidPayment after send Request with body [authToken: ". $request->authToken.", transID:".$request->transactionID ."] and response status is: ". $response->status() . " and Body is: " . $response->body()]);
            if($response->status() == 200) {
                $response = json_decode($response->body(),true);
                if($response["obj"]["is_voided"] == true){
                    Payment::create([ "type" => "VOID-TRANSACTION" ]);
                    return true;
                }    
            }
        }catch(\Exception $e){
            DB::table('test')->insert(["request" => "inside void fail : " . $e->getMessage()]);
        }
        return false;
    }
    
    // return iframe for user for 1st payment
    public function getIframeUrl(Request $request){
        $request->amount = $request->amount < 10 ? 10 : $request->amount;
        $paymentToken = $this->getCardPaymentKey($request,false);
        if(!$paymentToken) return null;
       
        return response()->json(["link" => "https://accept.paymobsolutions.com/api/acceptance/iframes/".env("PAYMOB_IFRAME_ID")."?payment_token=".$paymentToken, "paymentAuthKey" => $this->authKey ]);
    }
    
    // the callback function when payment done
    public function iframeCallback(Request $request){
        if($request->input("type") == "TOKEN"){
            $payment = Payment::where("payment_order_id",intval($request->input('obj.order_id')))->first();
            // Check for repeated card
            $card = Card::withTrashed()->where([["card_number", $request->input("obj.masked_pan")],["user_id", $payment->user_id]])->first();
            if($card){
                ($card->trashed() ? $card->restore(): '');
                $card->update([
                    "identifier_token" => $request->input("obj.token"),
                    "gateway_response" => json_encode($request->input())
                    ]);
            }else{
                Card::create([
                    "user_id" => $payment->user_id,
                    "card_number" => $request->input("obj.masked_pan"),
                    "card_type" => $request->input("obj.card_subtype"),
                    "identifier_token" => $request->input("obj.token"),
                    "gateway_response" => json_encode($request->input()),
                    "gateway" => "paymob"
                ]);
            }
          
        }
        
        if($request->input("type") == "TRANSACTION"){
            $payment = Payment::where("payment_order_id",$request->input('obj.order.id'))->first();
            $payment->update(["response_data" => $request->input('obj'),"type" => "ADD-CARD"]);
            $paymobOrderId = $payment->payment_order_id;
            $card = Card::where("gateway_response","LIKE","%\"order_id\":\"$paymobOrderId\"%")->first();
            
              try
                {
                    // void payment
                    $voidRequest = new Request();               
                    $voidRequest->merge(["authToken" =>  $payment->token,"transactionID" =>  $request->input('obj.id')]);
                    $this->voidPayment($voidRequest);
                    
                }catch(\Exception $e){
                    DB::table('test')->insert(["request" => $e->getMessage()]);
                }
                // Handle failed transactions
                if($request->input('obj.success') == true){
                        ($card->trashed() ? $card->restore(): '');
                }else{
                        $relatedOrder = Operation::whereIn("status",[1, 2, 4])->where("card_id",$card->id)->count();
                        if(!$card->trashed() && !$relatedOrder){
                            $card->delete();
                        };

                }
            }
        return;
    }
    
    // the callback to check Payment Completion
    // public function checkPaymentCompletion($completed = false){
    //     $completed = $this->$paymentCompleted;
    //     return $completed;
    // }
    
    // Pay with saved token (MOTO)
    public function payWithSavedToken($order){
        // Request => [ Amount, userId, cardId ]
        $request = request()->merge(["amount" => $order->amount,"userId" => $order->user_id,"cardId" => $order->card_id]);
        $paymentToken = $this->getCardPaymentKey($request);
        if(!$paymentToken) return null;
        $card = Card::find($order->card_id);
        
        $url = "https://accept.paymobsolutions.com/api/acceptance/payments/pay";
        $response = HTTP::post($url,[
            "source" => 
    			[     
                    "identifier"=> $card->identifier_token,     
                    "subtype"=> "TOKEN"
                ],
            "payment_token"=> $paymentToken
            ]);
        if($response->status() == 200){
            $responseBody = json_decode($response->body(),true);
            $payment = Payment::create([
                "user_id" => $request->userId,
                "payment_order_id" => $responseBody["order"],
                "token" => $this->authKey,
                "response_data" => $response->body(),
                "type" => "TRANSACTION-MOTO-RESPONSE"
            ]);

            // successfull operation
            return ["status" => $responseBody["success"] == "true" ? true : false,"payment_id" => $payment->id];
        }
        return ["status" => false,"payment_id" => null];
    }
    // Pay with saved token (MOTO) Callback
    public function motoCallback(Request $request){
        $payment = Payment::where("payment_order_id",$request->input('obj.order.id'));
        $payment->update(["response_data" => $request->input('obj'),"type" => "TRANSACTION-MOTO-CALLBACK"]);
    }

    // Refund Amount 
    public function refund(int $amount,int $operation){
        $url = "https://accept.paymob.com/api/acceptance/void_refund/refund";
        $secretKey = "Token " . env("PAYMOB_API_SECRET");
        $operation = Operation::with("payment")->find($operation);
        $transaction_id = $operation->payment->response_data ? json_decode($operation->payment->response_data)["id"] : null;
        // return false if entries is invalid
        if(!$transaction_id || $amount < 1 || $amount > $operation->amount) return false;

        $response = HTTP::withHeaders(["Authorization" => $secretKey])->post($url,[
            "amount_cents" => ($amount * 100),
            "transaction_id" => $transaction_id
        ]);

        if($response->status() == 200){
            $responseBody = json_decode($response->body(),true);
            return true;
        }

        return false;
    }
}