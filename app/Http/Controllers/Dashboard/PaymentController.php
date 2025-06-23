<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Api\FawryPayController;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\OperationsController;
use App\Http\Controllers\Api\PaymobController;
use App\Models\Operation;
use App\Models\Payment;
use App\Models\Refund;
use App\Models\Setting;

class PaymentController extends Controller
{
    public $gatways = [
        'fawry' => FawryPayController::class,
        'paymob' => PaymobController::class
    ];
    
    public $defaultGatway;

    public function __construct(){
        $this->defaultGatway = Setting::where("key","payment_gateway")->first()->value;
    }

    public function requestFailedPayments($order = null){
       $actions = new OperationsController();
       if(!$order){
           $orders = Operation::where("status",4)->get();
            foreach($orders as $order){
                $actions->completePayment($order);
            }
            return $orders;
       }
       $order = Operation::find($order);
       $actions->completePayment($order);
       return $order;
    }

    public function create(){
        $orders = Operation::where("status",4)->get();
        return view("dashboard.payments.create",compact("orders"));
    }

    public function store(Request $request){
        if(isset($request->order)){
            $order = $this->requestFailedPayments($request->order);
            return redirect()->route("dashboard.payments.create")->with("success",__("Order :id amount requested successfully",["id" => $order->id]));
        }
        $orders = $this->requestFailedPayments();
        return redirect()->route("dashboard.payments.create")->with("success",__("All Orders amounts requested successfully"));
    }

    public function incompletePaymentSettingsUpdate(Request $request){
        $request->validate([
           'duration' => 'required|numeric',
        ]);
        
        Setting::updateOrCreate(
            ['key' => 'incomplete_auto_request_duration'],
            ['value' => $request->duration]
        );

        return redirect()->back()->with('success', __('Auto request has been updated.'));
    }

    public function requestMultiplePayments(Request $request){
        $request->validate([
            'orders' => 'required|exists:operations,id',
        ]);

        $orders = [];
        foreach(explode(',',$request->orders) as $order){
            $orders["ids"] = $this->requestFailedPayments($order);
        }

        return redirect()->back()->with("success",__("Orders :ids amount requested successfully",["ids" => $request->orders]));
    }

    // Edit Incomplete order amount
    public function editIncompleteOrderAmount(Request $request){
        $request->validate([
            'amount' => 'required|numeric',
            'order' => 'required|exists:operations,id',
        ]);

        $order = Operation::find($request->order);
        $order->update(["amount" => $request->amount,"status" => ($request->amount == 0 ? 3 : $order->status)]);
        
        $order->incompleteOperation->update([
            "final_amount" => $request->amount,
            "status" => ($request->amount == 0 ? 'Deleted' : null),
            "ended_at" => ($request->amount == 0 ? now() : null)
        ]);

        return redirect()->back()->with("success",__("Order :id updated successfully",["id" => $order->id]));
    }
    
    // Refund operation Amount
    public function refund(Request $request) {

        $request->validate([
            'operation_id' => 'required|exists:operations,id',
            'amount' => 'required|numeric|min:1',
        ]);
        
        $operation = Operation::find($request->operation_id);
        if(!$operation) {
            return redirect()->back()->with("error",__("Order :id not found",["id" => $request->operation_id]));
        }

        $payment = $operation->payment_id ? Payment::find($operation->payment_id) : null;
        if(!$payment) {
            return redirect()->back()->with("error",__("Order :id isn't paid yet",["id" => $operation->id]));
        }
        
        $provider = $payment ? $payment->provider : null;
        $controller = $this->gatways[$provider ?? $this->defaultGatway];

        $refund = (new $controller())->refund($request->amount,$request->operation_id);
        
        // Failed refund
        if(!$refund) return redirect()->back()->with("error",__("Order :id amount refund failed",["id" => $operation->id]));
        
        // Storing refund
        Refund::create([
            "operation_id" => $request->operation_id,
            "amount" => $request->amount,
            "reason" => $request->reason ?: null,
        ]);
        
        // Update operation amount
        $operation->update(["amount" => ($request->amount - $operation->amount <= 0 ? 0 : $operation->amount - $request->amount)]);
        
        return redirect()->back()->with("success",__("Order :id amount refunded successfully",["id" => $operation->id])); 
    }

}