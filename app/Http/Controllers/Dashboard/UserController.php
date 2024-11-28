<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRequest;
use App\Models\IncompleteHistory;
use App\Models\Operation;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $registerdUsers = User::count();
        $activeUsers = Operation::with('user')->distinct('user_id')->count();
        $incompleteAutoRequestDurations = [ 1 => "Every day", 7 => "Every Week", 30 => "Every Month", 365 => "Every Year" ];
        $incompleteDuration = Setting::where('key', 'incomplete_auto_request_duration')->first();
        $incompleteOperations = Operation::where('status', 4)->with('device','user','incompleteOperation')->orderByDesc('updated_at');
        OperationController::checkForIncompleteOperations($incompleteOperations->get());
        $incompleteOperations = $incompleteOperations->limit(5)->get();

        return view('dashboard.users.index', compact('incompleteAutoRequestDurations', 'incompleteDuration','incompleteOperations','registerdUsers','activeUsers'));
    }
    
    /**
     * Display user's Operation.
    */
    public function showOperations(Request $request,string $id)
    {
        // Date Filter
        $startDate = $request->startDate ?? null;
        $endDate = $request->endDate ?? null;

        $user = User::with('operations')->find($id);
        $amountTimeData = [0,0,0,0,0,0]; // [totalAmount, totalHours, amountPerPeriod, timePerPeriod, amountForLastMonth, TimeForLastMonth]
        foreach($user->operations as $operation){
            $amountTimeData[0] += ($operation->amount ?? 0);
            $amountTimeData[1] += ($operation->returnTime && $operation->borrowTime ? floatval((strtotime($operation->returnTime) - strtotime($operation->borrowTime) )/ 60 /60) : 0);
            
            // Amount And Hours per Selected period
            if($operation->borrowTime && ($startDate || $endDate)){
                if(($startDate && $operation->borrowTime >= $startDate) || ($endDate && $operation->borrowTime <= $endDate)){
                    $amountTimeData[2] += ($operation->amount ?? 0);   
                    $amountTimeData[3] += ($operation->returnTime && $operation->borrowTime ? floatval((strtotime($operation->returnTime) - strtotime($operation->borrowTime) )/ 60 /60) : 0);
                }
            }
            // Amount And Hours per Last Month
            if($operation->borrowTime >= now()->previous('Month')){
                $amountTimeData[4] += ($operation->amount ?? 0);   
                $amountTimeData[5] += ($operation->returnTime && $operation->borrowTime ? floatval((strtotime($operation->returnTime) - strtotime($operation->borrowTime) )/ 60 /60) : 0);
            }
        }

        return view('dashboard.users.user-operation',compact('user','amountTimeData','startDate','endDate'));
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $user = user::find($user->id);
        return view("dashboard.users.edit",compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $validated = $request->validated();
        $validated["phone"] = str_starts_with($validated["phone"],0) ? substr($validated["phone"],1) : $validated["phone"];
        $user = User::where("id",$user->id);
        // dd($validated,$user->first()->password);
        $user->update([
            "first_name" => $validated["first_name"],
            "last_name" => $validated["last_name"],
            "email" => $validated["email"],
            "password" => ($validated["password"] ?  $validated["password"] : $user->first()->password),
            "code" => $validated["code"],
            "phone" => $validated["phone"],
            "updated_by" => $validated["updated_by"],
        ]);
        return redirect()->route("dashboard.users.index")->with("Success",__("User updated successfully"));
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('dashboard.users.index')->with('success', __(':resource has been deleted.', ['resource' => __('User')]));
    }
}
