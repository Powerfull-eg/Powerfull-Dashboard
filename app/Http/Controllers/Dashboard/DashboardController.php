<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Device;
use App\Models\Operation;
use App\Models\Shop;
use App\Models\Ticket;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Show the dashboard.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function __invoke()
    {
        $data = $this->indexData();
        $admin = auth('admins')->user();
        $roles = $admin->getRoleNames()->toArray();
        $adminType = count($roles) && !$admin->hasRole('shopAdmin') ? 'personnel' : ($admin->hasRole('shopAdmin') ? 'shopAdmin' : 'superAdmin');
        return view('dashboard.index',['data' => $data, 'adminType' => $adminType]);
    }

    private function indexData() : array
    {
        $data = [];
        // Models
        $operations = new Operation; 
        $users = new User;
        $support = new Ticket;
        $shops = new Shop;
        
        // Data Getters
        # Operations
        $allOperations = $operations->all();
        $inCompletedOperations = $operations->where('returnTime'.null)->get();
        $inCompletedPaymentOperations = $operations->where('status','!=',3)->get();
        // Start from last friday
        $operationsPerLastWeek = $operations->where('created_at','>=',now()->isFriday() ? now()->startOfDay() : new Carbon('last friday'))->get();
        $operationsThisMonth = $operations->where('created_at','>=',date("Y-m-d",mktime(0,0,0,date('m'),1,date('Y'))));
        
        $operationsLastMonth = $operations->where('created_at','>=',date("Y-m-d",mktime(0,0,0,date('m')-1,1,date('Y'))))
                                          ->where('created_at','<',date("Y-m-d",mktime(0,0,0,date('m'),1,date('Y'))));

        $data['allOperations'] = $allOperations;
        $data['inCompletedOperations'] = $inCompletedOperations;
        $data['inCompletedPaymentOperations'] = $inCompletedPaymentOperations;
        $data['operationsPerLastWeek'] = $operationsPerLastWeek;
        $data['operationsThisMonth'] = $operationsThisMonth->count();
        $data['operationsLastMonth'] = $operationsLastMonth->count();
        
        // Get last 3 months operations
        $last3months = [date('m')-3,date('m')-2,date('m')-1];
        foreach($last3months as $key => $month){
            $monthName = date('F',mktime(0,0,0,$month,1,date('Y')));
            $last3monthsOperations[$monthName] = $operations->where('created_at','>=',date("Y-m-d",mktime(0,0,0,$month,1,date('Y'))))
                                                            ->where('created_at','<',date("Y-m-d",mktime(0,0,0,$month+1,1,date('Y'))))
                                                            ->count();
        }
        $data['last3monthsOperations'] = $last3monthsOperations ?? [];
        
        # Revenues
        $revenuePerAllOperations = $allOperations->sum('amount');
        $data['revenuePerAllOperations'] = $revenuePerAllOperations;

        #Users
        $allUsers = $users->all();
        $usersThisMonth = $users->where('created_at','>=',date("Y-m-d",mktime(0,0,0,date('m'),1,date('Y'))))->count();
        $data['allUsers'] = $allUsers;
        $data['usersThisMonth'] = $usersThisMonth;
        
        # Active Users
        $regularCustomers = $operationsThisMonth->pluck('user_id')->countBy(fn($val) => $val++);
        $regularCustomers = array_filter($regularCustomers->toArray(), fn($value) => $value > 1);
        $top10 = $users->withCount('operations')->orderByDesc('operations_count')->limit(10)->get();
        $data['regularCustomers'] = $regularCustomers;
        $data['top10'] = $top10;

        # Support
        $data['allTickets'] = $support->count();
        $data['newTickets'] = 0;
        foreach($support->with('lastMessage')->get() as $ticket){
            $ticket->lastMessage->first()->sender == 1  ? $data['newTickets']++ : null;
        }

        #Shops
        $latestOperationsShops = [];
        foreach($operations->orderByDesc('created_at')->limit('10')->get() as $operation){
                $latestOperationsShops[] = [
                    "shop" => $operation->device->shop,
                    "operation" => $operation,
                    'user' => $users->find($operation->user_id)->fullName ?? null
                ];
        }
        $data['allShops'] = $shops;
        $data['latestOperationsShops'] = $latestOperationsShops;
        
        $data['top10Shops'] = Device::with('shop')->withCount('operations')->orderByDesc('operations_count')->limit(10)->get();
        return $data;
    }
}
