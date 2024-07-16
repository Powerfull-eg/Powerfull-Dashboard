<?php

namespace App\Jobs;

use App\Models\Shop;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class CloneShops implements ShouldQueue
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
        $providers = config('devices-providers');
        $shops = [];

        foreach($providers as $provider => $controller)
        {
                $data = json_decode($controller->getShops()->getContent(),true)[1];
                $data = json_decode($data, true)["data"];
                DB::table('test')->insert(["request" => json_encode($data) ]);
                foreach($data as $details)
                {
                    $shops[] = [
                        "name" => $details['shopName'],
                        "provider_id" => $details['newID'],
                        "logo" => $details['shopBanner'] ?? null,
                        "icon" => $details['shopIcon'] ?? null,
                        "governorate" => 'Cairo',
                        "city" => 'Cairo',
                        "address" => $details['shopAddress'] ?? null,
                        "location_latitude" => $details['latitude'],
                        "location_longitude" => $details['longitude'],
                    ];
                }
        }
        // Add Shops and update if exists
        foreach($shops as $shop){
            $existShop = Shop::where('provider_id',$shop['provider_id']);
            if($existShop->count()){
                $existShop->update($shop);
                continue;
            }
            $newShop = Shop::create($shop);
        }
    }
}
