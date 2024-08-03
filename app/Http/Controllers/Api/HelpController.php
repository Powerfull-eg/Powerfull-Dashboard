<?php

namespace App\Http\Controllers\Api;

use App\Mail\NotifySupportMail;
use App\Models\Message;
use App\Models\Ticket;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\WhatsappController;

class HelpController extends \App\Http\Controllers\Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => []]);
    }

    // Adding new Ticket
    public function addTicket(Request $request){
        
        $user = Auth::guard('api')->getuser();
        
        $validated = $request->validate([
            "subject" => "required|string",
            "message" => "required|string"
        ]);
        
        $ticket = Ticket::create([
            "user_id" => $user->id,
            "subject" => $validated["subject"]
        ]);

        Message::create([
            "message" => $validated["message"],
            "ticket_id" => $ticket->id,
            "sender" => 1
        ]);
        // Send Autoreply for 1st message
        if($ticket->messages->count() <= 1){
            $this->autoReply($ticket->id);
        
        // send Whatsapp notification
                $user = User::find($ticket->user_id);
                $url = route("dashboard.support.edit", $ticket->id);
                $notificationMessage = "لديك طلب تواصل جديد من $user->fullName" . "\r\n" . $url;
                $whatsRequest = new Request();
                $whatsRequest->merge(["mobile" => "01069170097", "message" => $notificationMessage]);
                $whatsapp = new WhatsappController();
                $whats = $whatsapp->sendTextMessage($whatsRequest);
        }
        
        return response()->json(["ticket_id" => $ticket->id]);
    }
    
    // Get all tickets for user
    public function getTickets(Request $request){
        $user = Auth::guard('api')->getuser();
        
        $tickets = Ticket::where("user_id",$user["id"])->orderBy('updated_at', 'desc')->get();
        return response()->json(["tickets" => $tickets]);
    }

    // Get specifiv ticket for user
    public function getTicket(Request $request,string $id){
        $user = Auth::guard('api')->getuser();
        
        $ticket = Ticket::where(["user_id"=>$user["id"],"id" => $id])->with("messages")->get();
        return response()->json(["ticket" => $ticket]);
    }

    // add message to a ticket
    public function addMessage(Request $request){
        $validated = $request->validate([
            "id"=>"required",
            "message" => "required|string"
        ]);

        $ticket = Ticket::where("id", $request->id)->first();
        
        $message = Message::create([
            "message" => $validated["message"],
            "ticket_id" => $ticket->id,
            "sender" => 1
        ]);
        // Update Ticket
        $ticket->update(["updated_at" => Carbon::now()]);
        
        return response()->json(["message" => $message]);
    }

    // Add Automatic reply
    public function autoReply($ticketId) 
    {
        $message_ar = "أهلا بحضرتك نحن نهتم
                    بالشكوي والمقترحات من عملائنا يتم الرد خلال ساعة بحد اقصى";
                    
        $message_en = "Welcome , We are careful about your message and  we will reply within an hour";
        $message = Message::create([
            "message" => $message_ar,
            "ticket_id" => $ticketId,
            "sender" => 2 
            ]);
            
        $message->create([
            "message" => $message_en,
            "ticket_id" => $ticketId,
            "sender" => 2
            ]);
    }
}
