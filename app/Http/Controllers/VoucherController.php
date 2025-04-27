<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Voucher;
use App\Models\Operation;
use Illuminate\Support\Facades\DB;

class VoucherController extends Controller
{
    // Voucher Types [ 0 => percentage, 1 => amount]

    // Generate a new voucher
    public function generate(Request $request) {
        $validated = $request->validate([
            "code" => "required|string",
            "user_id" => "nullable|exists:users,id",
            "type" => "required|in:0,1",
            "value" => "required|integer",
            "min_amount" => "required|integer",
            "max_discount" => "required|integer",
            "from" => "required",
            "to" => "required",
            "image" =>"nullable",
            "multiple_usage" => "boolean",
            "usage_count" => "integer"
        ]);

        $voucher = Voucher::create($validated);

        return $voucher; 
    }

    // Validate the voucher min amount
    private function validateVoucherMinAmount(int $orderAmount, Voucher $voucher) {
        // check minimum amount
        if($orderAmount < $voucher->min_amount) return false; // "Order amount is less than the required amount"   
        return true;
    }
    // Validate the voucher max discount
    private function validateVoucherMaxDiscount(int $voucherAmount, Voucher $voucher) {
        // check minimum amount
        if($voucherAmount > $voucher->max_discount) return false; // "Voucher amount is more than the max discount"   
        return true;
    }

    // calculate voucher amount
    public function claculateVoucher(Request $request){
        // Request => [int orderAmount, voucher_id]
        $voucherAmount = 0;
        $voucher = Voucher::find($request->voucher_id);
        // percentage
        if($voucher->type === 0 && $this->validateVoucherMinAmount($request->orderAmount,$voucher) == true)
        {
            $voucherAmount = intval($request->orderAmount * $voucher->value / 100);
            $voucherAmount = $this->validateVoucherMaxDiscount($voucherAmount,$voucher) ? $voucherAmount : $voucher->max_discount;
        }
        elseif($voucher->type === 1 && $this->validateVoucherMinAmount($request->orderAmount,$voucher) == true)
        {
            $voucherAmount = $voucher->value;
        }
        return $voucherAmount;
    }
}