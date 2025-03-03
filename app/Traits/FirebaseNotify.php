<?php

namespace App\Traits;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Cache;

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
    public function notify(string $token, string $title, string $body, string $image = null,array $data = [], bool $validation=false)
    {
        $accessToken = $this->getFirebaseAccessToken();

        if (!$accessToken) {
            return response()->json(['error' => 'Failed to get Firebase access token'], 500);
        }

        $headers = [
            'Authorization' => 'Bearer ' . $accessToken, // Important!
            'Content-Type' => 'application/json',
        ];

        $payload = [
            'message' => [
                'token' => $token,
                'notification' => [
                    'title' => $title,
                    'body' => $body,
                    "image" => $image ?? null,
                ],
            ],
        ];

        $url = "https://fcm.googleapis.com/v1/projects/" . env('FIREBASE_PROJECT_ID') . "/messages:send";
        
        $response = Http::withHeaders($headers)->post($url, $payload);
        if($response->status() != 200 && env('APP_DEBUG') == 'true'){
            dump($response->body());
        }

        return $response->status() == 200 ? true : false;
    }

    function getFirebaseAccessToken()
    {
        if (Cache::has('key')) {
            return Cache::get('firebase_access_token');
        }

        // Get the service account credentials
        $serviceAccountPath = storage_path('firebase-admin.json');
        $credentials = json_decode(file_get_contents($serviceAccountPath), true);
        $now = time();

        // Create the JWT payload
        $jwtPayload = [
            'iss' => $credentials['client_email'],
            'sub' => $credentials['client_email'],
            'aud' => 'https://oauth2.googleapis.com/token',
            'iat' => $now,
            'exp' => $now + 3600,
            'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
        ];

        // Sign the JWT using the private key
        $jwt = JWT::encode($jwtPayload, $credentials['private_key'], 'RS256');

        // Exchange JWT for an access token
        $response = Http::post("https://oauth2.googleapis.com/token", [
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion' => $jwt,
        ]);

        if ($response->successful()) {
            Cache::put('firebase_access_token', $response->json()['access_token'], $now + 3600);
            return $response->json()['access_token'];
        }

        return null;
    }
}
