<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\OperationsController;
use App\Models\Operation;

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
}