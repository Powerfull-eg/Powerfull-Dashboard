<?php

namespace App\Traits;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

trait FirebaseNotify
{
    /**
     * Notify specific tokens using Firebase Legacy Api.
     */
    public function notifyLegacy(string|array $tokens, string $title, string $body, array $data): Response
    {
        $fields = [
            'data' => $data,
            'notification' => ['title' => $title, 'body' => $body],
        ];

        $key = is_array($tokens) ? 'registration_ids' : 'to';
        $fields[$key] = $tokens;

        $client = Http::withHeaders([
            'Content-Type: application/json',
            'Authorization: key=' . env('FIREBASE_SERVER_KEY'),
        ]);

        return $client->post('https://fcm.googleapis.com/fcm/send', $fields);
    }

    /**
     * Notify specific tokens using Firebase New Api.
     */
    public function notify(string $token, string $title, string $body,array $data = [], bool $validation=false): Response
    {
        $fields = [
            'validate_only' => $validation,
            'message' => [
                'name' => $title,
                'data' => $data,
                'notification' => [
                    'title' => $title,
                    'body' => $body,
                    'image' => $image ?? ''
                ],
                // options for android push notificiations
                // 'android'=> [
                // ],
                // options for web sdk push notificiations
                // 'webpush'=> [
                // ],
                // options for apple push notificiations
                // 'apns' => [
                // ]
                'fcm_options' => [
                    'analytics_label' => $data['analytics_label'] ?? ''
                ],
                'token' => $token,
            ]
        ];

        $client = Http::withHeaders([
            'Content-Type: application/json',
            'Authorization: key=' . env('FIREBASE_SERVER_KEY'),
        ]);
        $url = "https://fcm.googleapis.com/v1/{parent=projects/". env('FIREBASE_PROJECT_NUMBER'). "}/messages:send";

        return $client->post($url, $fields);
    }
}
