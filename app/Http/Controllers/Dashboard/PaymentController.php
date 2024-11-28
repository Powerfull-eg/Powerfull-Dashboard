<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\OperationsController;
use App\Models\Operation;
use App\Models\Setting;

class PaymentController extends Controller
{
    public function requestFailedPayments($order = null){
       $actions = new OperationsController();
       $request = new Request();
       if(!$order){
           $orders = Operation::where("status",4)->get();
            foreach($orders as $order){
                $request->merge(["orderId" => $order->id]);
                $actions->completePayment($request);
            }
            return $orders;
       }
       $order = Operation::find($order);
       $request->merge(["orderId" => $order->id]);
       $actions->completePayment($request);
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
            // $orders["ids"] = $this->requestFailedPayments($order);
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
}