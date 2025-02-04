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
            $payment = $actions->completePayment($request);
            $this->addIncompletedPaymentToHistory($order,$payment,$payment ? "paid" : "null");
        }
    }

    /*
    * Add Incompleted Payment to history
    */
    public static function addIncompletedPaymentToHistory($operation,$done = false ,$status = null){
        // Add operation to incomplete history
        $exists = DB::table('incomplete_history')->where('operation_id', $operation->id)->exists();
        
        if (!$exists) {
            DB::table('incomplete_history')->insert([
                'operation_id' => $operation->id,
                'original_amount' => $operation->amount,
            ]);
        }else{
            DB::table('incomplete_history')->where('operation_id', $operation->id)->update([
                'final_amount' => $operation->amount,
                'status' => $done ? $status : null,
                'ended_at' => $done ? now() : null
            ]);
        }
    }
}