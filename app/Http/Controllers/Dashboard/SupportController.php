<?php

namespace App\Http\Controllers\Dashboard;


use App\Models\Message;
use App\Models\Ticket;
use App\Models\User;
use App\Notifications\SupportNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
class SupportController extends Controller
{
    /*
    * Get All Mesasages 
    */ 
    public function index(Request $request)
    {
        $startDate = $request->startDate ?? null;
        $endDate = $request->endDate ?? null;
        return view('dashboard.support.index',compact("startDate","endDate"));
    }

    /*
    * Edit Message 
    */ 
    public function edit(string $id)
    {
        $ticket = Ticket::with("messages","user")->find($id);
        return view('dashboard.support.edit', compact("ticket"));
    }

    /*
    * Edit Message 
    */ 
    public function update(Request $request)
    {
        $validated = $request->validate([
            "message" => "string|required",
            "ticket_id" => "required",
            "admin_id" => "required|exists:admins,id",
            "sender" => "required"
        ]);

        $message = Message::create($validated);

        // Send Notification to user 
        if($message){
            $user = User::findorFail($message->ticket->user_id);
        }

        Ticket::find($validated["ticket_id"])->update(["updated_at" => Carbon::now(),'status' => 1]);
        return redirect()->back()->with('status','success');
    }

    // Update Specific Message
    public function updateMessage(Request $request)
    {
        $validated = $request->validate([
            "message" => "string|required",
            "message_id" => "required",
        ]);

        $message = Message::find($validated["message_id"]);
        if($message->admin_id != Auth::guard('admins')->user()->id){
            return redirect()->back()->with('error', __("You can't edit this message"));
        }
        
        $message->update($validated);
        return redirect()->back()->with('success',__("Message Updated Successfully"));
    }

    // End Ticket 
    public function endTicket(string $id)
    {
        if(!Auth::guard('admins')->user()){
            return redirect()->back()->with('error', __("You can't close this ticket"));
        }

        $closingMessage = __('Your inquiry has been solved, If you have any other questions, please don\'t hesitate to contact us.');

        $ticket = Ticket::with("messages","user")->find($id);

        $ticket->messages()->create([
            "message" => $closingMessage,
            "ticket_id" => $ticket->id,
            "admin_id" => Auth::guard('admins')->user()->id,
            "sender" => 0
        ]);

        $ticket->update(['status' => 2]);

        $ticket->user->notify(new SupportNotification($ticket->user, $closingMessage,'whatsapp'));

        return redirect()->back()->with('success',__("Ticket Closed Successfully"));
    }
}