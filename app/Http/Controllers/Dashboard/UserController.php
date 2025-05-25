<?php

namespace App\Http\Controllers\Dashboard;

use App\Exports\UsersExportExcel;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRequest;
use App\Models\BlockedAccount;
use App\Models\Gift;
use App\Models\IncompleteHistory;
use App\Models\Operation;
use App\Models\Setting;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Support\Facades\Password;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Date Filter
        $startDate = $request->startDate ?? null;
        $endDate = $request->endDate ?? null;

        $registerdUsers = User::count();
        $activeUsers = Operation::with('user')->distinct('user_id')->count();
        $incompleteAutoRequestDurations = [ 1 => "Every day", 7 => "Every Week", 30 => "Every Month", 365 => "Every Year" ];
        $incompleteDuration = Setting::where('key', 'incomplete_auto_request_duration')->first();
        OperationController::checkForIncompleteOperations();
        $incompleteOperations = Operation::where('status', 4)->with('device','user','incompleteOperation')->orderByDesc('updated_at')->limit(5)->get();
        $incompleteHistory = IncompleteHistory::with('operation')->orderByDesc('updated_at')->limit(5)->get();
      
        return view('dashboard.users.index', compact('incompleteAutoRequestDurations', 'incompleteDuration','incompleteOperations','incompleteHistory','registerdUsers','activeUsers','startDate','endDate'));
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
        return view("dashboard.users.edit",compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $request["phone"] = str_starts_with($request["phone"],0) ? substr($request["phone"],1) : $request["phone"];

        $user->update([
            "first_name" => $request["first_name"],
            "last_name" => $request["last_name"],
            "email" => $request["email"],
            "password" => ($request["password"] ?  Hash::make($request["password"]) : $user->password),
            "code" => $request["code"],
            "phone" => $request["phone"],
            "updated_by" => auth()->user()->id,
        ]);
        $user->createHistory(["action" => "Update User Account"]);

        return redirect()->back()->with("success",__("User :name updated successfully",["name" => $user->fullname]));
    }

    /**
     * Show user page
    */
    public function show(User $user){
        $statusStrings = ["New","Running","Not Paid","Done","Failed Payment"];
        $resetPasswordChannels = ['email','whatsapp','sms'];
        $operations = Operation::with('device','user')->where('user_id', $user->id)->withTrashed()->get();
        return view('dashboard.users.show',compact('user','statusStrings','resetPasswordChannels','operations'));
    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        $user->createHistory(["action" => "Delete User Account"]);
    
        return redirect()->route('dashboard.users.index')->with('success', __('User :user has been deleted.', ['user' => $user->fullname]));
    }

     /**
     * Restore User Data
     */
    public function restore(string $user)
    {
        $user = User::withTrashed()->find($user);
        $user->restore();
        $user->createHistory(["action" => "Restore User Account"]);
        
        return redirect()->route('dashboard.users.index')->with('success', __('User :user has been restored.', ['user' => $user->fullname]));
    }

     /**
     * Block User
     */
    public function block(Request $request,string $id)
    {
        $user = User::findOrFail($id);
        // Check if user is already blocked
        if ($user->blocked) {
            return redirect()->back()->with('error', __('User :user is already blocked.', ['user' => $user->fullname]));
        }

        $block = BlockedAccount::create([
            "user_id" => $user->id,
            "blocked_by" => auth()->user()->id,
            "reason" => $request->reason ?? null,
            "description" => $request->description ?? null
        ]);
        $user->createHistory(["action" => "Block User Account"]);

        return redirect()->back()->with($block ? 'success' : 'error', $block ? __('User :user has been blocked.', ['user' => $user->fullname]) : __('Failed to block user.'));
    }

    /**
     * Unblock User
     */
    public function unblock(string $id)
    {
        $user = User::findOrFail($id);
        $user->blocked()->delete();
        $user->createHistory(["action" => "Unblock User Account"]);
        return redirect()->back()->with('success', __('User :user has been unblocked.', ['user' => $user->fullname]));
    }

    /*
    * Reset User Password
    */
    public function resetPassword(string $id,Request $request){
        $user = User::findOrFail($id);
        $token = Password::createToken($user);

        // Send Reset Password Notification
        $user->notify(new ResetPasswordNotification($token,$user,$request->channels));

        // Create Reset Password History
        $user->createHistory(["action" => "Reset User Password"]);

        return redirect()->back()->with('success', __('User :user password has been reset.', ['user' => $user->fullname]));
    }

    /*
    * Add User's Gift
    */
    public function addGift(string $id){
        $user = User::findOrFail($id);
        $gifts = Gift::pluck('name','id');
        $shops = Shop::pluck('name','id');

        return view('dashboard.users.add-gift',compact('user','gifts','shops'));
    }

    /*
    * Store User's Gift
    */
    public function storeGift(string $id,Request $request){
        $user = User::findOrFail($id);
        $user->gifts()->create([
            "gift_id" => $request->gift_id,
            "shop_id" => $request->shop_id,
            "code" => $request->code,
            "expire" => $request->expire,
            "used_at" => $request->used_at ?? null
        ]);
        $user->createHistory(["action" => "add gift"]);

        return redirect()->route('dashboard.users.index')->with('success', __('User :user gift has been added.', ['user' => $user->fullname]));
    }
}
