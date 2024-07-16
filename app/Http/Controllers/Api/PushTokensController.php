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
        $token = $request->validate([
            "token" => "required"
        ]);

        $pushToken = PushToken::updateOrCreate(
            ['user_id' => $request->userId, 'token' => $token["token"]],
            ['token' => $token["token"], "device" => $request->device]
        );
        return response($pushToken);
    }
}
