<?php

namespace App\Http\Controllers\Api;
use App\Models\User;
use App\Models\Voucher;
use App\Models\VoucherOrder;
use App\Models\Operation;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
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
        $user = Auth::guard('api')->user();
        $allVouchers = Voucher::with('voucherOrder','campaign');
        $vouchers = [];
        
        // used vouchers
        $usedVouchers = VoucherOrder::where("user_id",$user['id'])->get(); 
        if(!$usedVouchers->isEmpty()) {
            foreach($usedVouchers as $voucher){
                $used[$voucher->id] = Voucher::where("id",$voucher->voucher_id)->first();
                $used[$voucher->id]['used_at'] = $voucher->added_at;
            }
        }
        // merge all used vouchers
        $vouchers['used'] = Arr::flatten($used ?? []);

        
        // Other vouchers
        $usedVouchersIds = Arr::map($vouchers['used'],fn($v) => $v->id);
        foreach($allVouchers->get() as $voucher){
                // skip if expired
                if ($voucher->user_id == $user['id'] && $voucher->to < now() && !in_array($voucher->id,$usedVouchersIds)) {
                    $vouchers['expired'][] = $voucher;
                    continue;
                }
                
                // skip if usage count already exceeded
                if ( 
                    (in_array($voucher->id, $usedVouchersIds) && $voucher->multiple_usage == 1 && $voucher->usage_count <= count(Arr::where($usedVouchersIds, fn($v) => $v == $voucher->id))) 
                    || $voucher->to < now()
                    )
                    continue;

                $vouchers['new'][] = $voucher;
        }
        return response()->json(["vouchers" => $vouchers]);
    }

    /**
     * Show target resource.
     */
    public function show(string $id)
    {
        $voucher = Voucher::find($id);
        $user = Auth::guard('api')->user();
        $usedVoucher = VoucherOrder::where("user_id",$user->id)->where("voucher_id",$voucher->id); 
        $voucher->used = $usedVoucher->exists() ? 1 : 0;
        
        return response()->json(["voucher" => $voucher]);
    }
}