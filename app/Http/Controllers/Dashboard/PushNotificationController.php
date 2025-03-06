<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\PushNotification;
use App\Http\Controllers\Controller;
use App\Models\PushToken;
use App\Models\User;
use App\Traits\FirebaseNotify;
use Illuminate\Http\Request;

class PushNotificationController extends Controller
{
    use FirebaseNotify;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dashboard.push-notifications.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // pluck users by full name (first + last) and id as key
        $users  = User::all('id','first_name','last_name')->pluck('full_name','id')->toArray();

        $targets = [0 => __("All App Users"),1 => __("Registered Users"),2 => __("Unregistered Users"),3 => __("Specific Users")];
        return view('dashboard.push-notifications.create', compact('users','targets'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
           'title' => ['required', 'string', 'max:255'],
           'body' => ['required', 'string', 'max:255'],
           'target' => ['required', 'integer', 'in:0,1,2,3'],
           'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
           'data' => ['nullable', 'json'],
           'url_image' => ['nullable', 'url'],
           'users' => ['required_if:target,3', 'array', 'exists:users,id'],
        ]);

        $targets = [];
        switch ($validated['target']) {
            // All users
            case 0:
                $targets = PushToken::all();
                break;
            // Registered users
            case 1:
                $targets = PushToken::whereNotNull('user_id')->get();
                break;
            // Unregistered users
            case 2:
                $targets = PushToken::whereNull('user_id')->get();
                break;
            // Multiple users
            case 3:
                $targets = PushToken::whereIn('user_id', $validated['users'])->get();
                break;
        }
        if($targets->count() < 1) return redirect()->back()->with('error', 'No users exists for this notification');
        
        // Handle Image 
            if(isset($validated['image'])){
                $image = $validated['image']->store('public/notifications');
                $image = url(str_replace('public/', 'storage/', $image));
            } else if(isset($validated['url_image'])){ {
                $image = $validated['url_image'];
            }
        }

        $success = $failed = 0;
        foreach($targets as $target){
            $data = [
                'token' => $target->token,
                'title' => $validated['title'],
                'body' => $validated['body'],
                'image' => $image ?? null,
                'data' => $validated['data'] ?? [],
            ];
            $sent = $this->send($data);
            if($sent){
                $success++;
                PushNotification::create([
                   'token' => $target->token,
                   'title' => $validated['title'],
                   'body' => $validated['body'],
                   'user_id' => $target->user_id ?? null,
                   'image' => $image ?? null,
                   'data' => json_encode($validated['data'] ?? [])
                ]);
            }
            else {
                $failed++;
            }
        }

        return redirect()->back()->with($success == 0 ? 'error' : 'success',$success > 0 ? ('Notification sent successfully to ' . $success . ' users' . ($failed > 0 ? ' but ' . $failed . ' failed' : '')) : 'Failed to send notification to ' . $failed . ' users');

    }

    /**
     * Send Notification.
     */
    public function send(array $data)
    {
        return $this->notify($data['token'], $data['title'], $data['body'],$data['image'],isset($data['data']) && count($data['data']) > 0 ? $data['data'] : []);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PushNotification $pushNotification)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PushNotification $pushNotification)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PushNotification $pushNotification)
    {
        $pushNotification->delete();
    }
}