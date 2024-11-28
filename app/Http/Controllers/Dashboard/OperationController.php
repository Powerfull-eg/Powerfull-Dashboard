<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Operation;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BajieController;
use App\Models\IncompleteHistory;
use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Support\Arr;

class OperationController extends Controller
{
    public $operations;
    public $devices;

    public function __construct(){
        // $bajie = new BajieController;

        $this->operations = Operation::all();
        // $this->devices = $bajie->getDevices()->original;
    }
    

    public function index(Request $request){
        $operations = $this->operations;
        $startDate = $request->startDate;
        $endDate = $request->endDate;
        $operations = $request->startDate ? $operations->where("created_at",">",$request->startDate) : $operations;
        $operations = $request->endDate ? $operations->where("created_at","<",$request->endDate) : $operations;
        
        $operationsPerDevice = [];
        foreach($operations as $operation){
            if(in_array($operation->station_id,array_keys($operationsPerDevice)))
            {
                array_push($operationsPerDevice[$operation->station_id], $operation);
                continue;
            }
            $operationsPerDevice[$operation->station_id] = [$operation];
        }
        
        return view("dashboard.operations.index", compact('operations','operationsPerDevice','startDate','endDate'));
    }

    public function getOperationData(string $id) {
        $operation = Operation::where('id',$id)->with('incompleteOperation','device','user','device.shop')->first();
        $operation->borrowTime = $operation->borrowTime ? chineseToCairoTime($operation->borrowTime) : $operation->borrowTime;
        $operation->returnTime = $operation->returnTime ? chineseToCairoTime($operation->returnTime) : $operation->returnTime;
        return $operation;
    }

    public static function checkForIncompleteOperations($operations){
        foreach($operations as $operation){
            if($operation->incompleteOperation) continue; 

            IncompleteHistory::create([
                "operation_id" => $operation->id,
                "original_amount" => $operation->amount
            ]);
        }   
        return false;
    }
}
