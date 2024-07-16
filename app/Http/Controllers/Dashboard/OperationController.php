<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Operation;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BajieController;
use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Support\Arr;

class OperationController extends Controller
{
    public $operations;
    public $devices;

    public function __construct(){
        $bajie = new BajieController;

        $this->operations = Operation::all();
        $this->devices = $bajie->getDevices()->original;
        // $shops = json_decode($bajie->getShops()->original[1],true)["data"] ?? null;
        // dd($this->devices);
    }

    public function index(){
        $operations = $this->operations;
        $operationsperDevice = $operations->groupBy("station_id");
        $labels = Arr::get();
        $devices = $this->devices;
        // $shops = $this->shops;
        return view("dashboard.operations.index", compact('operations','operationsperDevice','devices'));
    }

    
}
