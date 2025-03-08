<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PushNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PushNotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::guard('api')->user();
        return response()->json([ "notifications" => PushNotification::where('user_id', $user->id)
                                     ->selectRaw('MAX(id) as id, 
                                            title, 
                                            body, 
                                            MAX(image) as image, 
                                            MAX(data) as data, 
                                            MAX(seen) as seen, 
                                            MAX(created_at) as created_at
                                        ')
                                        ->groupBy('user_id', 'body', 'title') // Group by user_id, body, and title to avoid duplicates
                                        ->orderBy('created_at', 'desc')
                                        ->limit(10)
                                        ->get()
                                ]);
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

  
    /**
     * Get User's notification not read count.
     */
    public function getNotificationCount() {
        $user = Auth::guard('api')->user()->id;
        return response()->json([ "count" => PushNotification::where('user_id', $user)->where('seen', 0)
                                ->selectRaw('MAX(id) as id, 
                                            title, 
                                            body')
                                        ->groupBy('body', 'title') // Group by user_id, body, and title to avoid duplicates
                                 		->get()->count()
                                ]);
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
        $notification = PushNotification::find($id);
        PushNotification::where('title',$notification->title)->where('user_id',$notification->user_id)->update(['seen' => 1]);

        return response()->json([ "notification" => $notification ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}