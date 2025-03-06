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
        return response()->json([ "notifications" => PushNotification::where('user_id', $user->id)->limit(10)->groupBy('body')->orderBy('created_at', 'desc')->get(['id', 'title', 'body','image','data','seen','created_at']) ]);
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
        $notification->seen = 1;
        $notification->save();

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
