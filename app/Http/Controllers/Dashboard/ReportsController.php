<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\Operation;
use App\Models\Shop;
use App\Models\Ticket;
use App\Models\User;
use Error;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    private $shops;
    private $devices;
    private $operations;
    private $users;

    public function index(Request $request)
    {
        //Shops Data
        $this->shops = $request->startDate ? Shop::where('created_at','>=',$request->startDate) : new Shop;
        $this->shops = $request->endDate ? $this->shops->where('created_at','<=',$request->endDate) : $this->shops;

        //Devices Data
        $this->devices = $request->startDate ? Device::where('created_at','>=',$request->startDate) : new Device;
        $this->devices = $request->endDate ? $this->devices->where('created_at','<=',$request->endDate) : $this->devices;

        //Operations Data
        $this->operations = $request->startDate ? Operation::where('created_at','>=',$request->startDate) : new Operation;
        $this->operations = $request->endDate ? $this->operations->where('created_at','<=',$request->endDate) : $this->operations;

        //Users Data
        $this->users = $request->startDate ? User::where('created_at','>=',$request->startDate) : new User;
        $this->users = $request->endDate ? $this->users->where('created_at','<=',$request->endDate) : $this->users;

        $target = $request->target ?: 'shops';
        $request->target = $target;
        $data = $this->getTargetData($request);
        $startDate = $request->startDate;
        $endDate = $request->endDate;
        return view('dashboard.reports.index',compact('target','data','startDate','endDate'));
    }

    private function getTargetData($request)
    {
        $target = $request->target;
        
        $targets = [
            'shops' => "getShopsData",
            'devices' => "getDevicesData",
            'customers' => "getCustomersData",
            'financial'=> "getFinancialData"
        ];

        if(!in_array($target,array_keys($targets))) throw new Error("Defined target $target is not exist");
        
        foreach ($targets as $key => $value) {
            if  ($key !== $target) continue;
            $data = $this->$value($request);
        }
        
        return $data;
    }

    // Shops Data
    private function getShopsData(Request $request){

        $shops = $this->shops->with('device','operations')->get();
        // Summary data
        $summary["totalCompanyDevices"] = "77";
        $summary["activeDevices"] = $this->devices->count();
        $summary["DevicesInStores"] = $this->devices->where('shop_id','!=',null)->count();
        $summary["DevicesWaitingContract"] = $this->devices->where('shop_id',null)->count();
        $summary["totalOperationsOrders"] = $this->operations->count();
        $summary["totalOperationsHours"] = 0;
        foreach($this->operations->get() as $operation){
            $summary["totalOperationsHours"] += ($operation->returnTime && $operation->borrowTime ? floatval((strtotime($operation->returnTime) - strtotime($operation->borrowTime) )/ 60 /60) : 0);
        }
        $summary["totalOperationsHours"] = round($summary["totalOperationsHours"],0);
        $shops->summary = $summary;
        return $shops;
    }

    // Devices Data
    private function getDevicesData(Request $request){
        $devices = $this->devices->with('shop','operations')->get();
        // Summary data
        $summary["totalCompanyDevices"] = "77";
        $summary["activeDevices"] = $this->devices->count();
        $summary["totalOperationsOrders"] = $this->operations->count();
        $summary["totalPowerbanks"] = 0; // counted on page
        $summary["totalLossPowerbanks"] = 0; // counted on page
        $summary["totalOperationsHours"] = 0;
        foreach($this->operations->get() as $operation){
            $summary["totalOperationsHours"] += ($operation->returnTime && $operation->borrowTime ? floatval((strtotime($operation->returnTime) - strtotime($operation->borrowTime) )/ 60 /60) : 0);
        }
        $summary["totalOperationsHours"] = round($summary["totalOperationsHours"],0);
        $devices->summary = $summary;

        return $devices;
    }

    // Customers Data
    private function getCustomersData(Request $request){
        $users = $this->users->with('operations')->get();
        // Summary data
        $summary["numOfDownloadedApp"] = $users->count();
        $summary["customersMakeOperations"] = $this->operations->select('user_id', DB::raw('COUNT(*) as operation_count'))->groupBy('user_id')->get()->count();
        $summary["numComplaintsResolved"] = 0;
        $summary['registeredCustomers'] = $users->count();
        $summary['regularCustomers'] = $this->operations->select('user_id', DB::raw('COUNT(*) as operation_count'))->groupBy('user_id')->having('operation_count', '>', 1)->get()->count();
        $summary["numComplaintsOpen"] = 0;
        foreach(Ticket::all() as $ticket){
            $ticket->lastMessage()->first()->sender == 2 ? $summary["numComplaintsResolved"]++ : $summary["numComplaintsOpen"]++;
        }
        $users->summary = $summary;
        return $users;
    }

    // Financial Data
    private function getFinancialData(Request $request){
        // $startDate = $request->startDate ?? null;
        // $endDate = $request->endDate ?? null;
        // $financial = new Financial;
        // $financial = $startDate ? $financial->where('created_at','<=',$startDate) : $financial;
        // $financial = $endDate ? $financial->where('created_at','>=',$endDate) : $financial;
        // $financial = $financial->with('device','data')->get();
        $financial = (object) [];
        $summary["totalIncome"] = $this->operations->sum('amount');
        $summary["totalNetProfit"] = $this->operations->sum('amount');
        $summary["totalOperationsOrders"] = $this->operations->count();
        $summary["totalDiscounts"] = 0;
        $summary["incompletePayments"] = $this->operations->where('status',4)->count();
        $summary["totalOperationsHours"] = 0;
        foreach($this->operations->get() as $operation){
            $summary["totalOperationsHours"] += ($operation->returnTime && $operation->borrowTime ? floatval((strtotime($operation->returnTime) - strtotime($operation->borrowTime) )/ 60 /60) : 0);
        }
        $summary["totalOperationsHours"] = round($summary["totalOperationsHours"],0);
        $financial->summary = $summary;
        return $financial;
    }
}
