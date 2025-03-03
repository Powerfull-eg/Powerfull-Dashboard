<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WhatsappController extends Controller
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
     */
    public static function sendTextMessage(Request $request)
    {
        $validated = $request->validate([
            "mobile" => "required",
            "message" => "required",
        ]);
        
        $url = "https://app.arrivewhats.com/api/send?number=2".$validated["mobile"]."&type=text&message=".$validated["message"]."&instance_id=". env('ARRIVEWHATS_INSTANCE_ID')."&access_token=". env('ARRIVEWHATS_ACCESS_TOKEN');
        $response = Http::post($url);
        $body = json_decode($response->body(), true);
        if(isset($body["status"]) && $body["status"] == "success"){
            return [true, $body];
        }
        return [false, $response];
    }

    /**
     * Display the specified resource.
     */
    public static function sendFileWithMessage(Request $request)
    {
        $validated = $request->validate([
            "mobile" => "required",
            "message" => "nullable|string",
            "file" => "required",
        ]);
        $message = (isset($validated["message"]) ? ($validated["message"] . ' \n')  : '') . $validated['file'];

        $request = new Request();
        $request->merge([ "mobile" => $validated["mobile"], "message" => $message]);
        dump($request->message);
        $response = static::sendTextMessage($request);
        dd($response);
        return $response[0];
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