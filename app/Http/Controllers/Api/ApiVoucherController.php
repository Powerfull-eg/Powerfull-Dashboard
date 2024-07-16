<?php

namespace App\Http\Controllers\Api;
use App\Models\User;
use App\Models\Voucher;
use App\Models\VoucherOrder;
use App\Models\Operation;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;

class ApiVoucherController extends \App\Http\Controllers\Controller
{
    /**
     * Create a new Voucher instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::guard('api')->getuser();
        $usedVouchers = VoucherOrder::where("user_id",$user['id'])->pluck("added_at","voucher_id")->toArray(); 
        $vouchers = Voucher::where("from",'<',now())
                            ->whereIn("user_id",[0,$user["id"]])
                            ->get();

        foreach($vouchers as $voucher){

            if(array_key_exists($voucher->id,$usedVouchers))
            {
                $voucher["used_at"] = $usedVouchers[$voucher->id];
                $used[] = $voucher;
            }
            elseif($voucher->to < now())
            { 
                $expired[] = $voucher; 
            }
            else
            {
                 $new[] = $voucher; 
            }
        }
        $vouchers = ["new" => $new ?? [], "used" => $used ?? [], "expired" => $expired ?? []];
        return response()->json(["vouchers" => $vouchers]);
    }

}