<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SMSController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     * Send New Message Immediately
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "mobile" => "required",
            "message" => "required",
            "language" => "required"
        ]);
        $validated['message'] = rawurlencode($validated['message']);
        $url = "https://smsmisr.com/api/SMS/?environment=".env('SMS_ENVIRONMENT')."&username=".env('SMS_MISR_USERNAME')."&password=".env('SMS_MISR_PASSWORD')."&language=".$validated['language']."&sender=". env('SMS_MISR_SENDER_TOKEN_LIVE'). "&mobile=2".$validated['mobile']."&message=".$validated['message'];
        $response = Http::post($url);
        $body = json_decode($response->body(), true);

        // Add SMS and save response to DB -------
        DB::table('channels')->insert( [
            "type" => 1,
            "content" => $validated["message"],
            "reciever" => $validated["mobile"],
            "response" => $response->body(),
            "status" => $body["code"] == "1901" ? 1 : 2
        ]);

        // Success
        if($body["code"] == "1901"){
                return true;
        }
        return false;
    }
    
    /**
    * Send OTP to users
    * SMS MISR
    */
    public static function sendOTP(Request $request)
    {
        $validated = $request->validate([
            "mobile" => "required",
            "otp" => "required|numeric",
        ]);
                
        $template = "e83faf6025ec41d0f40256d2812629f5fa9291d05c8322f31eea834302501da8";
        $sender = env('SMS_MISR_SENDER_TOKEN_LIVE');
        $user = env('SMS_MISR_USERNAME');
        $password = env('SMS_MISR_PASSWORD');
        
        $url = "https://smsmisr.com/api/OTP/?environment=2&username=$user&password=$password&sender=$sender&mobile=$validated[mobile]&template=$template&otp=$validated[otp]";

        try {
            $response = Http::post($url);
            $body = json_decode($response->body(), true);

            // Add SMS and save response to DB -------
            DB::table('channels')->insert( [
                "type" => 1,
                "content" => $validated['otp'],
                "reciever" => $validated["mobile"],
                "response" => $response->body(),
                "status" => $body["code"] == "4901" ? 1 : 2
            ]);

            // Success
            if($body["code"] == "4901"){
                    return true;
            }
        } catch (\Throwable $th) {
            return false;
        }

        return false;
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}