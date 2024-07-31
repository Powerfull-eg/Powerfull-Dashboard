<?php

namespace App\Http\Controllers\Dashboard;


use App\Models\Message;
use App\Models\Ticket;
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
        $ticket = Ticket::with("messages")->find($id);
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

        Message::create($validated);
        Ticket::find($validated["ticket_id"])->update(["updated_at" => Carbon::now(),'status' => 1]);
        return redirect()->back()->with('status','success');
    }
}