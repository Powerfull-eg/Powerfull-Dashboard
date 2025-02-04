<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Operation;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BajieController;
use App\Models\IncompleteHistory;
use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Request as FacadesRequest;

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

    public static function checkForIncompleteOperations(){
        $operations = Operation::where('status',4)->get();
        foreach($operations as $operation){
            if($operation->incompleteOperation) continue; 

            IncompleteHistory::create([
                "operation_id" => $operation->id,
                "original_amount" => $operation->amount,
              	"created_at" => now(),
              	"updated_at" => now()
            ]);
        }   
        return false;
    }

    // Delete Operation
    public function destroy(Operation $operation){
        $operation->status = 3;
        $operation->deleted_by = auth()->user()->id;
        $operation->delete();
        $operation->save();
        $operation->user->createHistory(["action" => "Delete Order"]);

        return redirect()->back()->with("success",__("Operation :id deleted successfully",["id" => $operation->id]));
    }

    /**
     * Restore Operation
     */
    public function restore(string $id)
    {
        $operation = Operation::withTrashed()->find($id);
        $operation->restore();
        $operation->deleted_by = auth()->user()->id;
        $operation->save();
        $operation->user->createHistory(["action" => "Restore Order"]);

        return redirect()->back()->with('success', __('Operation :id has been restored.', ['id' => $operation->id]));
    }

    /** 
    * Close Operation
    */
    public function closeOrder(string $id, Request $request) {
        $operation = Operation::find($id);
        if($operation->status != 1){
            return redirect()->back()->with('error', __('Operation :id is not open.', ['id' => $operation->id]));
        }

        // Close Operation
        $operation->status = 2;
        $operation->returnTime = $operation->returnTime ?? now()->toISOString();
        $operation->save();
        $operation->user->createHistory(["action" => "Close Order"]);

        return redirect()->back()->with('success', __('Operation :id has been closed.', ['id' => $operation->id]));
    }

    /** 
    * Refund Operation Amount
    */
    public function refundOrder(string $id,Request $request){
        $request->validate(['amount' => 'required']);

        $operation = Operation::find($id);
        $operation->user->createHistory(["action" => "Refund Order"]);
        if($operation->status != 2){
            return redirect()->back()->with('error', __('Operation :id is not closed.', ['id' => $operation->id]));
        }

    }
}
