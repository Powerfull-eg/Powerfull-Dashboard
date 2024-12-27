<?php

namespace App\Jobs;

use App\Models\Provider;
use App\Models\Shop;
use App\Models\ShopsData;
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
        $providors = Provider::all();
        $shops = [];
        
        foreach($providors as $provider){
            $shops[] = $provider->controller->getShops();
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
