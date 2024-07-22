<?php

namespace App\Http\Controllers;

use App\Models\Operation;
use App\Models\Price;
use Illuminate\Http\Request;

class PriceController extends Controller
{
    // Show prices for dashboard
    public function index(){
        $prices = Price::all();
        return view('dashboard.prices.index',compact('prices'));
    }

    // Edit Prices
    public function edit(string $id){
        $price = Price::find($id);
        return view('dashboard.prices.edit',compact('price'));
    }
    
    // Update Prices
    public function update(Request $request, string $id){
        $validated = $request->validate([
            'prices.*.*.*' => 'required',
            'free_time' => 'required|numeric',
            'max_hours' => 'required|numeric',
            'insurance' => 'required|numeric',
        ]);
        // Create prices json
        $prices = [];
        foreach($request->prices as $type => $priceDeatails){
            $i = 0;
            $prices[$type][$i] = [];
            foreach($priceDeatails as $value){
                if(in_array(array_key_first($value),array_keys($prices[$type][$i]))){ $i++; }
                foreach($value as $k => $v){
                    $prices[$type][$i][$k] = $v;
                }
            }
        }
        $price = Price::find($id)->update([
            'free_time' => $validated['free_time'],
            'max_hours' => $validated['max_hours'],
            'insurance' => $validated['insurance'],
            'prices' => json_encode($prices),
            "created_by" => auth('admins')->user()->id,
            "updated_by" => auth('admins')->user()->id,
        ]);

        return redirect()->route('dashboard.prices.index')->with('success',__('Prices Updated Successfully'));
    }
    
    // get Price Description
    public function getPriceDescription(){
        $priceData = Price::latest()->first();
        $prices = json_decode($priceData->prices,true);
        $description = [];
        // Free Time
        if($priceData["free_time"] > 0){
            $description[] = " مجانا لمدة " . $priceData["free_time"] . ($priceData["free_time"] > 10 ? " دقيقة " : " دقائق") . " | ";
        }
        // Dynamic Prices
        foreach($prices['dynamic'] as $price){
            $description[] = $price['description'] . ' - ' .$price["price"] . ' جنيه لكل ساعة | ';
        }
        // Static Prices
        foreach($prices['static'] as $price){
            $description[] = $price['description'] . ' - ' .$price["price"] . ' جنيه ';
        }
        // insurance if exceeded the max_hours
        if($priceData["insurance"]){
            $description[] = " في حالة عدم الإرجاع قبل ". $priceData["max_hours"] . " ساعة سيتم دفع مبلغ تأمين بقيمة " . $priceData["insurance"];
        }
        return response()->json([$description]);
    }
    
    // Calculate Price
    public function calcuatePrice(Request $request){
        $order = Operation::find($request->orderId);
        $priceData = Price::latest()->first();
        $totalTime = (strtotime($order->returnTime) - strtotime($order->borrowTime));
        $timeWithoutFree = $totalTime - ($priceData->free_time * 60);
        if($timeWithoutFree <= 0) return 0; 
        $timeInHours = ceil($timeWithoutFree / 60 / 60);
        
        // Check if Time more than max_time
        if($timeInHours > $priceData->max_hours) return $priceData->insurance;
        
        // calculate time
        $prices = json_decode($priceData->prices,true);

        // If time applied to static prices
        if($timeInHours >= $prices["static"][0]["from"]){
            foreach($prices["static"] as $price){
                if($timeInHours >= $price["from"] && $timeInHours <= $price["to"]) return $price["price"];
            }
        // If time applied to dynamic prices
        }else{
            $amount = 0;
            foreach($prices["dynamic"] as $price){
                for($i = $price["from"]; $i <= $price["to"]; $i++){
                    if($timeInHours <= 0) return $amount;
                    $amount += $price["price"];
                    $timeInHours--;
                }
            }
            return $amount;
        }
    }
}