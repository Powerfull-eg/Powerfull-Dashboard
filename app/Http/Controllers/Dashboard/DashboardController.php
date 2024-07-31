<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Operation;
use App\Models\Shop;
use App\Models\Ticket;
use App\Models\User;

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
        return view('dashboard.index',compact('data'));
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
        $operationsPerLastWeek = $operations->where('created_at','>=',now()->sub('1 week'))->get();
        $data['allOperations'] = $allOperations;
        $data['inCompletedOperations'] = $inCompletedOperations;
        $data['inCompletedPaymentOperations'] = $inCompletedPaymentOperations;
        $data['operationsPerLastWeek'] = $operationsPerLastWeek;

        # Revenues
        $revenuePerAllOperations = $allOperations->sum('amount');
        $revenuePerLastWeek = $operationsPerLastWeek->sum('amount');
        $data['revenuePerAllOperations'] = $revenuePerAllOperations;
        $data['revenuePerLastWeek'] = $revenuePerLastWeek;

        #Users
        $allUsers = $users->all();
        $usersPerLastMonth = $users->where('created_at','>=',now()->sub('1 month'))->get();
        $data['allUsers'] = $allUsers;
        $data['usersPerLastMonth'] = $usersPerLastMonth;
        
        # Active Users
        $activeUsers = $operationsPerLastWeek->pluck('user_id')->countBy(fn($val) => $val++);
        $top10 = $users->withCount('operations')->orderByDesc('operations_count')->limit(10)->get();
        $data['activeUsers'] = $activeUsers;
        $data['top10'] = $top10;

        # Support
        $allTickets = $support->all();
        $newTickets = $support->where('status',0)->get();
        $data['allTickets'] = $allTickets;
        $data['newTickets'] = $newTickets;

        #Shops
        $allShops = $shops->all();
        $newShops = $shops->where('created_at','>=',now()->sub('1 month'));
        $latestOperationsShops = [];
        foreach($operations->orderByDesc('created_at')->limit('5')->get() as $operation){
                $latestOperationsShops[] = [
                    "shop" => $operation->device->shop,
                    "operation" => $operation,
                    'user' => $users->find($operation->user_id)->fullName ?? null
                ];
        }
        $data['allShops'] = $allShops;
        $data['newShops'] = $newShops;
        $data['latestOperationsShops'] = $latestOperationsShops;
        
        return $data;
    }
}
