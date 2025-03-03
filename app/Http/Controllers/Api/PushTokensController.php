<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PushToken;

class PushTokensController extends Controller
{
    // Add tokens to database and edit it if exist
    public function upsertToken(Request $request)
    {
        // Request => [ userId, device, token ]
        $validated = $request->validate([
            "token" => "required"
        ]);

        $pushToken = PushToken::where("token",$validated["token"]);

        if($pushToken->exists()){
            $pushToken->update([
               'user_id' => $request->userId ?? ($pushToken->user_id ?? null),
               'device' => $request->device ?? ($pushToken->device ?? null),
               'token' => $validated["token"]
            ]);
            return response('Token updated successfully');
        }

        $pushToken = PushToken::create([
            'user_id' => $request->userId ?? null,
            'device' => $request->device ?? null,
            'token' => $validated["token"]
        ]);
        return response('Token added successfully');
    }
}
