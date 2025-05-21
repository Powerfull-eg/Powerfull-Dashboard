<?php

namespace App\Http\Controllers\Api;

use App\Models\Card;
use App\Models\Operation;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Sleep;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Api\FawryPayController;

class UserController extends \App\Http\Controllers\Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['']]);
    }

        /* Add card to user database */
    // public function addCard(Request $request)
    // {
    //     $user = Auth::guard('api')->getuser();
        
    //     $exist = Card::where([ ["card_number", $request->cardNumber ],["user_id" , $user->id]])->count();
    //     if($exist)
    //         return response("Card is already exist",204);
        
    //     $fawry = new FawryPayController();
    //     $authCard = $fawry->authCard($request);
    //     return response($authCard,400);
        
        
    //     // Card::create([
    //     //     'user_id' => $user->id,
    //     //     "card_holder" => $request->cardHolder,
    //     //     "card_number" => $request->cardNumber,
    //     //     "exp_month" => $request->month,
    //     //     "exp_year" => $request->year,
    //     //     "cvv" => $request->cvv,
    //     // ]);

    //     $userCards = $user->cards;
    //     return response()->json(["userCards" => $userCards]);
    // }
    
    /* Remove card from user database */
    public function removeCard(Request $request){
        $user = Auth::guard('api')->getuser();
        $card = Card::find($request->card_id);
        $orderRelated = Operation::where([["card_id",$card->id],["status","!=","3"]])->count();
        
        if(!$card){ return response()->json(["msg" => "card is not exist"],204); }
        elseif($orderRelated){return response()->json(["msg" => "Can't remove card because it's related to incompleted order"],201);}

        $card->delete();
        return response()->json(["msg"=> "Card deleted successfully"],200);
    }
    
    // Get user Cards 
    public function getCards(){
        $user = Auth::guard('api')->getuser();
        $cards = Card::where("user_id" , $user["id"])->get();
        return response()->json(["cards" => $cards]);
    }
    
    // Get Orders 
    public function getOrders(Request $request){
        $user = Auth::guard('api')->getuser();
		$startDate = date('Y-m-d H:i:s', $request->startdate ? intval($request->startdate / 1000) : 0);
		$endDate = date('Y-m-d H:i:s', $request->enddate ? strtotime('last day of this month 23:59:59', intval($request->enddate  / 1000)) : time());
		$page = $request->page ?? 1;
        $limit = $request->limit ?? 10;
        
      	$operations = Operation::where("user_id" , $user["id"])->whereBetween('created_at',[$startDate , $endDate]);
      	$count = $operations->count();
      	$limitedOperations = $operations->limit($limit)->offset($limit * ($page - 1))->orderBy('updated_at', 'desc')->get();
      	return response()->json(["orders" => $limitedOperations,"count" => $count]);
    }

    // Get Specific Order 
    public function getOrder(Request $request,string $id){
        $user = Auth::guard('api')->getuser();

        $operation = Operation::where(["user_id" => $user["id"],"id" => $id])->first();
      
         // Handling time for Bajie chinese time 
        $operation->borrowTime = $operation->borrowTime ? date('Y-m-d H:i:s', strtotime($operation->borrowTime) - 5 * 3600) : $operation->borrowTime;
        $operation->returnTime = $operation->returnTime ? date('Y-m-d H:i:s', strtotime($operation->returnTime) - 5 * 3600): $operation->returnTime;
        
        return response()->json(["order" => $operation]);
    }
    
    // Get Specific Order by trade Number
    public function getOrderByTradeNo(Request $request){
        $user = Auth::guard('api')->getuser();
        $operation = Operation::where("tradeNo", $request->tradeNo)->first();
        return response()->json(["order" => $operation]);
    }
    
    // Update user Data
    public function updateUser(Request $request){
        $user = Auth::guard('api')->getuser();
        
        // validate data
        $data = $request->validate([
                    "first_name" => "string|nullable",
                    "last_name"  => "string|nullable",
                    "email"      => "email|nullable",
                    "phone"      => "numeric|nullable",
                    "password"   => "nullable",
        ]);
        
        // hash password
        if(isset($data["password"])){
            $data["password"] = Hash::make($data["password"]);
        }
        
        // Update user
        User::find($user->id)->update($data);
        $user = User::find($user->id);
        // get user returned data
        $userData = [
            'id' => $user->id, 
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'phone' => $user->phone,
            'photo' => $user->avatar ?? '',
            'cards' => $user->cards ?? []
        ];
        
        return response()->json($userData);
    }
}
