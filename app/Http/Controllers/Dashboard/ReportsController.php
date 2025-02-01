<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\Operation;
use App\Models\Shop;
use App\Models\Ticket;
use App\Models\User;
use App\Services\PdfService;
use Error;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExportExcel;
use App\Exports\ReportExportExcel;
use App\Exports\ShopExportExcel;
use PDF;

class ReportsController extends Controller
{
    private $shops;
    private $devices;
    private $operations;
    private $users;

    public function __construct(Request $request){
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
    }
    
    public function index(Request $request)
    {
        $target = $request->target ?: 'shops';
        $request->target = $target;
        $data = $this->getTargetData($target);
        $startDate = $request->startDate;
        $endDate = $request->endDate;
        return view('dashboard.reports.index',compact('target','data','startDate','endDate'));
    }

    private function getTargetData($target)
    {   
        $targets = [
            'shops' => "getShopsData",
            'devices' => "getDevicesData",
            'customers' => "getCustomersData",
            'financial'=> "getFinancialData"
        ];

        if(!in_array($target,array_keys($targets))) throw new Error("Defined target $target is not exist");
        
        foreach ($targets as $key => $value) {
            if  ($key !== $target) continue;
            $data = $this->$value();
        }
        // add dates to data
        if(request()->startDate || request()->endDate){
            $data->startDate = request()->startDate ?? null;
            $data->endDate = request()->endDate ?? null; 
        }

        return $data;
    }

    // Shops Data
    private function getShopsData(){

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
    // get Shop Data
    private function getShopData(string $id, $startDate = null, $endDate = null){
        $shop = Shop::find($id);
        $operations = $startDate? $shop->operations->where('created_at','>=',$startDate) : $shop->operations;
        $operations = $endDate? $operations->where('created_at','<=',$endDate) : $operations;

        $summary["totalIncome"] = $operations->sum('amount');
        $summary["totalNetProfit"] = $operations->sum('amount');
        $summary["totalOperationsOrders"] = $operations->count();
        $summary["incompletePayments"] = $operations->where('status',4)->count();
        $summary["totalOperationsHours"] = 0;
        foreach($operations as $operation){
            $summary["totalOperationsHours"] += ($operation->returnTime && $operation->borrowTime ? ceil(floatval((strtotime($operation->returnTime) - strtotime($operation->borrowTime) )/ 60 /60)) : 0);
        }
        $shop->summary = $summary;
        return $shop;
    }

    // Devices Data
    private function getDevicesData(){
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
    private function getCustomersData(){
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
    private function getFinancialData(){
        $financial = $this->devices->with('shop','operations')->get();
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

    // Report PDF Generator
    public function exportpdf($target){
        if(!$target) return redirect()->back()->with('error', __("Please select a right report"));
        
        $view = $target == 'customers' ? "dashboard.pdf.users" : "dashboard.pdf.reports";
        if($target == 'customers'){
            $data = $this->getTargetData($target);
        } else {
            $data = $this->devices->with('shop','operations')->get();
            $data->summary = $this->getTargetData($target)->summary;
            $data->target = $target;
        }
        
        // Add dates to data
        $data->startDate = $this->startDate ?? null;
        $data->endDate = $this->endDate ?? null;

        $pdf = PDF::loadView($view, ['data' => $data]);
        return $pdf->stream("$target.pdf");

    }
    // Report Pdf for specific shop
    public function exportShopPdf($id,Request $request){
        $view = "dashboard.pdf.shop";
        $data = $this->getShopData($id,$request->startDate,$request->endDate);
        $data->startDate = $request->startDate ?? null;
        $data->endDate = $request->endDate ?? null;
        $data->operations = $request->startDate ? $data->operations->where('created_at','>=',$request->startDate) : $data->operations;
        $data->operations = $request->endDate ? $data->operations->where('created_at','<=',$request->endDate) : $data->operations;
        $pdf = PDF::loadView($view, ['data' => $data]);
        return $pdf->stream("$data->name-report.pdf");
    }

    public function exportExcel($target) 
    {
        if(!$target) return redirect()->back()->with('error', __("Please select a right report"));
        if($target == 'customers'){
            $data = $this->getTargetData($target);
        } else {
            $data = $this->devices->with('shop','operations')->get();
            $data->summary = $this->getTargetData($target)->summary;
        }
        
        $data->targetExcel = $target;
        
        $excel = $target == 'customers' ? UsersExportExcel::class : ReportExportExcel::class;
        return Excel::download(new $excel($data), "$target.xlsx");
    }
    // Report Pdf for specific shop
    public function exportShopExcel($id,Request $request){
        $data = $this->getShopData($id,$request->startDate,$request->endDate);
        $excel = ShopExportExcel::class;
        return Excel::download(new $excel($data,$request->startDate,$request->endDate), "$data->name.xlsx");
    }

    // Show Device Report
    public function shopReport(string $id, Request $request){
        $startDate = $request->startDate ?? null;
        $endDate = $request->endDate ?? null;
        $shop = $this->getShopData($id,$startDate,$endDate);
        return view('dashboard.reports.show-shop',compact('shop','startDate','endDate'));
    }
}
