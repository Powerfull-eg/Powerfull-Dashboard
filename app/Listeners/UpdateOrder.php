<?php

namespace App\Listeners;

use App\Events\OrderPlaced;
use App\Models\Operation;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use PHPUnit\Framework\Constraint\Operator;

use function Pest\Laravel\json;

class UpdateOrder implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        Log::notice("listener running");
    }

    /**
     * Handle the event.
     */
    public function handle(OrderPlaced $event): void
    {
        Log::notice("listener handle");
        $operation = $event->operation;
        
        
        $dataUrl = "https://developer.chargenow.top/cdb-open-api/v1/rent/order/detail?tradeNo=".$operation->tradeNo;

        $response = Http::withBasicAuth(env("BAJIE_API_USERNAME"), env("BAJIE_API_PASSWORD"))->get($dataUrl);
        Log::debug($operation);
        Log::debug($response);
        $responseBody = json_decode($response->body(),true);
        if($response->status() == 200 && $responseBody['code'] == 0 ){
            Operation::where("id",$operation->id)->update([
                "powerbank_id" => $responseBody["data"]["batteryId"],
                "borrowTime" => $responseBody["data"]["borrowTime"],
                "returnTime" => $responseBody["data"]["returnTime"],
                "borrowSlot" => $responseBody["data"]["borrowSlot"]
            ]);
        }
    }

}
