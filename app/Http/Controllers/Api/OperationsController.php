<?php

namespace App\Http\Controllers\Api;

use App\Models\Operation;
use App\Models\Payment;
use App\Models\Station;
use App\Models\VoucherOrder;
use App\Models\Voucher;
use App\Models\IncompleteHistory;
use App\Jobs\CompleteFailedPayment;
use App\Http\Controllers\PriceController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\Api\PaymobController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OperationsController extends \App\Http\Controllers\Controller
{
    // Check for vouchers
    private function checkForVocuher(int $orderAmount,Request $request){
        $voucherOrder = VoucherOrder::where("order_id",$request->orderId)->first();
        if($voucherOrder == null) return 0;
        $request->merge(["orderAmount" => $orderAmount,"voucher_id" => $voucherOrder->voucher_id]);
        $voucher = new VoucherController;
        return $voucher->claculateVoucher($request);
    }

    // Proccess Payment for completed orders
    public function completePayment(Request $request){
        // Request => [ orderId ]
        try{
            $order = Operation::find($request->orderId);
            if($order->returnTime == null) return null;
            $price = new PriceController();
            $totalAmount = $order->amount ?? $price->calcuatePrice($request);
            $amount = intval($totalAmount - $this->checkForVocuher($totalAmount,$request));
            $request->merge(['amount' => $amount, "userId" => $order->user_id, "cardId"=>$order->card_id]);
            $paymob = new PaymobController();
            $paymentDone =  ($amount <= 0 ? ["status" => true,"payment_id"=> "0"] : $paymob->payWithSavedToken($request));
            DB::table('test')->insert(["request" => "Payment: " . json_encode($paymentDone)]);
            $order->update([
                "payment_id" => $paymentDone["payment_id"],
                "amount" => $totalAmount,
                "status" => ($paymentDone["status"] == true ? 3 : 4),
            ]);

            // Add operation to incomplete history
            $incompleteExists = IncompleteHistory::where('operation_id', $order->id)->first();
            (!$paymentDone["status"] || $incompleteExists ? CompleteFailedPayment::addIncompletedPaymentToHistory($order,$paymentDone["status"],$paymentDone["status"] == true ? "paid" : null) : null );
            return $paymentDone["status"];
        
        }catch(\Exception $err){ DB::table('test')->insert(["request" => "Operation Error: " . $err->getmessage()]);}
    }
    
}