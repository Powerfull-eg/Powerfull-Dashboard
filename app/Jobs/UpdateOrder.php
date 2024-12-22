<?php

namespace App\Jobs;

use App\Models\Operation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\OperationsController;
use App\Http\Controllers\PriceController;

class UpdateOrder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        $this->handle();
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
         $this->updateOperation();
         //$this->completePayment();
    }
    
    // Update Operation 
    protected function updateOperation() {
      	$inCompletedOrders = Operation::where("status", 1)->limit(10)->get();
        foreach ($inCompletedOrders as $order) {
            $url = "https://developer.chargenow.top/cdb-open-api/v1/rent/order/detail?tradeNo=".$order->tradeNo;
            $response = Http::withBasicAuth(env("BAJIE_API_USERNAME"), env("BAJIE_API_PASSWORD"))->get($url);
            $responseBody = json_decode($response->body(),true);
            if($responseBody['code'] == 0 && $responseBody["data"]["borrowStatus"] == 3){
                $price = new PriceController();
            	$request = new Request(['orderId' => $order->id]);
              	$amount = $price->calcuatePrice($request);
              	Operation::where("id",$order->id)->update([
                    "powerbank_id" => $responseBody["data"]["batteryId"],
                    "borrowTime" => $responseBody["data"]["borrowTime"],
                    "returnTime" => $responseBody["data"]["returnTime"],
                    "borrowSlot" => $responseBody["data"]["borrowSlot"],
                    "returnShop"   => $responseBody["data"]["returnShop"],
                    "status" => 4,
                  	"amount" => $amount
                ]);
              
            }
        }
    }
    
    // complete payment
    protected function completePayment() {
        $order = Operation::where("status", 2)->first();
        if(!$order) return;
        try{
            $actions = new OperationsController();
            $request = new Request(['orderId' => $order->id]);
            $actions->completePayment($request);
        }catch(\Exception $err){ 
            DB::table('test')->insert(["request" => "UpdateOrder catches: ".$err->getmessage()]);
        }
    }
}