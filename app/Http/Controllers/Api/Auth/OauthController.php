<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Http;
use Laravel\Socialite\Facades\Socialite;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Cache;

class OauthController extends Controller
{
    public $platforms;

    public function __construct()
    {
        $this->platforms = ['google', 'facebook', 'twitter'];
    }
    // Oauth Redirect
    public function oauthRedirect(Request $request)
    {
        if(!$request->platform || !in_array($request->platform, $this->platforms)) {
            return response()->json(['error' => 'Service not available right now'], 401);
        }

        return Socialite::driver($request->platform)->redirect();
    }

      // Validate user phone and send otp
    public function updateUserPhone(Request $request) {
        $request->validate([
          	'email' => 'required|email',
            'phone' => 'required|numeric',
            'otp' => 'required'            
        ]);

        // if otp is wrong
        if ($request->otp != Cache::get('otp')) {
            return response("otp is not correct", 401);
        }

        $user = User::where('email', $request->email)->first();
        $user->phone = $user->phone != null ? $user->phone : $request->phone;
        $user->save();

        $newToken = Auth::guard('api')->login($user);
        
        return response()->json(['token' => $newToken], 200);
    }
  
    // Authenticate Google Callback
    public function oauthGoogleCallback(Request $request) {
            $googleUser = Socialite::driver('google')->stateless()->user();
          	$phoneNumbers = Http::withToken($googleUser->token)->get('https://people.googleapis.com/v1/people/me?personFields=phoneNumbers,emailAddresses')->json();
          	$phoneNumber = isset($phoneNumbers['phoneNumbers']) && $phoneNumbers['phoneNumbers'][0]['value'] ? $phoneNumbers['phoneNumbers'][0]['value'] : null;
            
            $user = $phoneNumber ? User::where('phone', substr($phoneNumber,3))->first() : User::where('email', $googleUser->getEmail())->first();
            $requestPhone = false;
      		$name = explode(' ',$googleUser->getName());
      
          	if(!$phoneNumber && !$user) { $requestPhone = true; }    
          	elseif($phoneNumber && !$user) {
                // Check for egyptian number
                if(substr($phoneNumber, 0, 3) == "+20" && strlen(substr($phoneNumber,3)) == 10) {
                        $user = User::create([
                            'first_name' => $name[0],
                            'last_name' => $name[1],
                            'email' => $googleUser->getEmail(),
                          	'code' => substr($phoneNumber, 0, 3),
                          	'phone' => substr($phoneNumber,3),
                            'google' => $googleUser->getId(),
                            'password' => bcrypt(uniqid()), // Not used but required if using Laravel auth
                        ]);
                    
                } else { $requestPhone = true; }
            }
            else { // if user exist and phone isn't exist
              $user = $user ?? User::where('email', $googleUser->getEmail())->first();
              $requestPhone = true;
            }

          	// create user if not exist
          	if (!$user) {
              $user = User::create([
                'first_name' => $name[0],
                'last_name' => $name[1],
                'email' => $googleUser->getEmail(),
                'google' => $googleUser->getId(),
                'password' => bcrypt(uniqid()),
              ]);
            }
          	
            $appBundleId = setting('bundle_id');
          	// request phone from user
			if($requestPhone && !$user->phone) return redirect("$appBundleId://oauth_callback?requestPhone=true&email=".$user->email);
            
          	// Generate JWT Token
            $token = JWTAuth::fromUser($user);

            // Redirect back to the mobile app with the token
            return redirect("$appBundleId://oauth_callback?token=$token");
    }

    // Authenticate Facebook Callback
    public function oauthFacebookCallback(Request $request) {
        $appBundleId = setting('bundle_id');

        try {
            $facebookUser = Socialite::driver('facebook')->stateless()->user();

            $user = User::where('email', $facebookUser->getEmail())->first();

            if (!$user) {
                $user = User::create([
                    'name' => $facebookUser->getName(),
                    'email' => $facebookUser->getEmail(),
                    'google_id' => $facebookUser->getId(),
                    'password' => bcrypt(uniqid()), // Not used but required if using Laravel auth
                ]);
            }

            // Generate JWT Token
            $token = JWTAuth::fromUser($user);
            // Redirect back to the mobile app with the token
            return redirect("$appBundleId://oauth_callback?token=$token");
        } catch (\Exception $e) {
            return redirect("$appBundleId://oauth_callback?error=" . $e->getMessage(),401);
        }
    }

    // Authenticate Twitter Callback
    public function oauthTwitterCallback(Request $request) {
        $appBundleId = setting('bundle_id');

        try {
            $googleUser = Socialite::driver('google')->stateless()->user();

            $user = User::where('email', $googleUser->getEmail())->first();

            if (!$user) {
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'password' => bcrypt(uniqid()), // Not used but required if using Laravel auth
                ]);
            }

            // Generate JWT Token
            $token = JWTAuth::fromUser($user);

            // Redirect back to the mobile app with the token
            return redirect("$appBundleId://oauth_callback?token=$token");
        } catch (\Exception $e) {
            return redirect("$appBundleId://oauth_callback?error=" . $e->getMessage());
        }
    }
}