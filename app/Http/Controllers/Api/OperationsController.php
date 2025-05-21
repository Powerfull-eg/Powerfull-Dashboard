<?php

namespace App\Http\Controllers\Api;

use App\Models\Operation;
use App\Models\Payment;
use App\Models\Station;
use App\Models\VoucherOrder;
use App\Models\Voucher;
use App\Jobs\CompleteFailedPayment;
use App\Http\Controllers\PriceController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\Api\PaymobController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OperationsController extends \App\Http\Controllers\Controller
{
    // Check for vouchers
    private function checkForVocuher(int $orderAmount,$orderId){
        $voucherOrder = VoucherOrder::where("order_id",$orderId)->first();
        if($voucherOrder == null) return 0;
        $voucher = new VoucherController;
        return $voucher->claculateVoucher($voucherOrder->voucher_id,$orderAmount);
    }

    // Proccess Payment for completed orders
    public function completePayment($order){
        // Request => [ orderId ]
        try{
            if($order->returnTime == null) return null;
            $price = new PriceController();
            $totalAmount = $order->amount != 0 ? $order->amount : $price->calcuatePrice($order);
          	$amount = $order->amount != 0 ? $totalAmount : intval($totalAmount - $this->checkForVocuher($totalAmount,$order->id));
            // $request->merge(['amount' => $amount, "userId" => $order->user_id, "cardId"=>$order->card_id]);
            $payment = new PaymentController();
            $paymentDone =  ($amount <= 0 ? ["status" => true,"payment_id"=> "0"] : $payment->payWithSavedToken($order));
            DB::table('test')->insert(["request" => "Payment: " . json_encode($paymentDone)]);
            $order->update([
                "payment_id" => $paymentDone["payment_id"],
                "amount" => $amount,
                "status" => ($paymentDone["status"] == true ? 3 : 4),
            ]);
             // Add operation to incomplete history
            ($paymentDone["status"] ? null : CompleteFailedPayment::addIncompletedPaymentToHistory($order,$paymentDone["status"],$paymentDone["status"] == true ? "paid" : null));
            return $paymentDone["status"];
        
        }catch(\Exception $err){ DB::table('test')->insert(["request" => "Operation Error: " . $err->getmessage()]);}
    }    
    
}