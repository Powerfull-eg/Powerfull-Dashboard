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
use App\Http\Controllers\Api\OperationsController;
use Illuminate\Http\Request;

class CompleteFailedPayment implements ShouldQueue
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
        $actions = new OperationsController();
        $orders = Operation::where("status",4)->get();
        $request = new Request();
        foreach($orders as $order){
            $request->merge(["orderId" => $order->id]);
            // $actions->completePayment($request);
        
        }
    }
}