<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Voucher;
use App\Models\Operation;

class VoucherOrder extends Model
{
    use HasFactory;
    protected $table = 'voucher_order';

    public $fillable = [
        "order_id",
        "voucher_id",
        "user_id"
    ];

    public function order() {
        return $this->belongsTo(Operation::class);
    }

    public function voucher() {
        return $this->belongsTo(Voucher::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

}
