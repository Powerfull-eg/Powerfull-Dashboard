<?php

namespace App\Http\Controllers\Api;

use App\Models\Card;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\SMSController;
use App\Http\Controllers\WhatsappController;

class AuthController extends \App\Http\Controllers\Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register', 'checkEmail', 'checkPhone', 'googleLogin', 'otp', 'checkOtp','otpActivate','resetPassword']]);
    }

    /**
     * send otp to user
    */
    public function otpActivate(Request $request)
    {
        $user = null;
        // check phone exist
        if ($this->checkPhone($request)->original == 1) {
            $user = User::where("phone", $request->phone)->first();
        }
        return response(["UserExist" => $user ? true : false, "name" => $user->first_name ?? null,"otpActive" => env("OTP_ACTIVE")], 200);
    } 
    /**
     * send otp to user
     */
    public function otp(Request $request)
    {
        $validate = $request->validate([
            "phone" => "required",
        ]);

            $randomNumber = random_int(1000, 9999);
            $expiresAt = now()->addMinutes(5);
            Cache::put('otp', $randomNumber, $expiresAt);
            $message = <<<EOT
    Your Powerfull Verification OTP is $randomNumber. 
    Please don't share it with anyone.
EOT;
        if(env("OTP_ACTIVE")){
            $otpRequest = new Request();
            $otpRequest->merge(["mobile" => "0" . $validate["phone"], "message" => $message, "language" => 2,"otp" => $randomNumber]);
            // $whatsapp = $validate["phone"] ? WhatsappController::sendTextMessage($otpRequest) : false;
            // Sms
            if($validate["phone"]){
                // Whatsapp
                $whatsapp = new WhatsappController();
                $whats = $whatsapp->sendTextMessage($otpRequest);
                $success = SMSController::sendOTP($otpRequest);
                $success = $success ?? ($whats[0] ? true : false);
            }else{
                $success = false;
            } 

            return response()->json([($success ? "Message sent successfully" : "Failed to send message")],($success ? 200 : 401));
        }
        
        if($request->type && $request->type == "email"){
                try{
                    $user = User::where('phone',$validate["phone"])->first();
                    Mail::raw($message, function ($message) use ($user) {
                            $message->to($user->email)
                            ->subject('PowerFull OTP');
                    });
                    return response()->json(["Message sent successfully"]);
                }catch(\Exception $e){
                    return response()->json(["Failed to send message: " . $e->getMessage()],401);
                }

        }
        return response("OTP Isn't Active");
    }

    /*
    * check otp veification 
    */
    public function checkOtp(Request $request)
    {
        // if otp is wrong
        if ($request->otp != Cache::get('otp')) {
            return response("otp is not correct", 401);
        }

        // check phone exist
        if ($this->checkPhone($request)->original == 1) {
            $user = User::where("phone", $request->phone)->first();
            return response(["User Exist", "name" => $user->first_name], 200);
        }

        return response(["User Not Exist"], 204);
    }
    /**
     * 
     * Check if email is exist
     *
     */
    public function checkEmail(Request $request)
    {
        $validate = $request->validate([
            "email" => "required|email",
        ]);

        $user = User::where("email", $validate["email"])->first();

        return response()->json($user ? 1 : 0);
    }
    /**
     * Check if email is exist
     *
     */
    public function checkPhone(Request $request)
    {
        $validate = $request->validate([
            "phone" => "required|numeric"
        ]);

        $user = User::where("phone", $validate["phone"])->first();

        return response()->json($user ? 1 : 0);
    }

    /**
     * login or register with google
     */
    public function googleLogin(Request $request)
    {

        $tokenParts = explode('.', $request->token);

        // Decode the payload part of the token
        $payload = base64_decode($tokenParts[1]);

        // Parse the JSON data from the decoded payload
        $decodedPayload = json_decode($payload);


        $user = User::updateOrCreate(
            ["email" => $decodedPayload->email],
            [
                "first_name" => $decodedPayload->given_name,
                "last_name" => $decodedPayload->family_name,
                "avatar" => $decodedPayload->picture,
                "google" => $decodedPayload->aud,
            ]
        );

        // Generate a JWT token for Laravel JWT authentication
        $token = JWTAuth::fromUser($user);

        // Store the JWT token in the Laravel session
        session(['jwt_token' => $token]);

        $userData = [
            'id' => $user->id,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'phone' => $user->phone,
            'photo' => $user->avatar ?? '',
            'cards' => $user->cards ?? []
        ];

        return response()->json(["token" => $this->respondWithToken($token)->original, "user" => $userData]);
    }

    /**
     * Register a new User.
     *
     * @return void
     */
    public function register(Request $request)
    {

        $validate = $request->validate([
            "password" => "required",
            "fname" => "required|string",
            "lname" => "required|string",
            "email" => "required",
            "phone" => "required|numeric"
        ]);
        $validate["first_name"] = $validate["fname"];
        $validate["last_name"] = $validate["lname"];
        $validate["password"] = Hash::make($validate["password"]);
        $validate["code"] = "+20";

        $user = User::create($validate);

        return response()->json($this->login($request));
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {

        $validations = [
            'email' => 'required_without:phone',
            'phone' => 'required_without:email',
            // 'password' => ['required']
        ];
        if(!env("OTP_ACTIVE")) $validations["password"] = 'required';
        $validate = $request->validate($validations);
        
        if(env("OTP_ACTIVE")){
            $user = User::where('phone',$request->phone)->first();
            $token = Auth::guard('api')->login($user);
        }else{
            $credentials = $request->only('phone', 'password');
            if (!$token = Auth::guard("api")->attempt($credentials)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            $user = Auth::guard('api')->getuser();
        }

        $userData = [
            'id' => $user->id,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'phone' => $user->phone,
            'photo' => $user->avatar ?? '',
            'cards' => $user->cards ?? []
        ];
        return response()->json(["token" => $this->respondWithToken($token)->original, "user" => $userData]);
    }

    /**
     * Login for old app versions.
     *
     * @return \Illuminate\Http\JsonResponse
    */
    public function loginLegacy(Request $request)
    {

        $validate = $request->validate([
            'email' => ['required_without:phone'],
            'phone' => ['required_without:email'],
            'password' => ['required']
        ]);

        $credentials = $request->only('phone', 'password');
        if (!$token = Auth::guard("api")->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = Auth::guard('api')->getuser();

        $userData = [
            'id' => $user->id,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'phone' => $user->phone,
            'photo' => $user->avatar ?? '',
            'cards' => $user->cards ?? []
        ];
        return response()->json(["token" => $this->respondWithToken($token)->original, "user" => $userData]);
    }
    public function get_user(Request $request)
    {
        $user = Auth::guard("api")->getuser();
        $response = $user ? ['user' => $user->id] : ['Message' => "UnAuthenticated"];
        return response()->json($response, $user ? 200 : 401);
    }
    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function authUser()
    {
        return response()->json(auth()->user());
    }


    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out'], 200);
    }

    /**
     * Reset the user's Password.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function resetPassword(Request $request)
    {
        $validate = $request->validate([
            // 'email' => ['required_without:phone'],
            'phone' => ['required'],
            'password' => ['required']
        ]);

        $user = User::where('phone', $validate['phone'])->update(['password' => Hash::make($validate['password'])]);
        return $this->login($request);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth("api")->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth("api")->factory()->getTTL()
        ]);
    }
}