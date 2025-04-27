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
        return $this->hasOne(Operation::class, "id", "order_id");
    }

    public function voucher() {
        return $this->hasOne(Voucher::class, "id", "voucher_id");
    }

    public function user() {
        return $this->hasOne(User::class, "id", "user_id")->withTrashed();
    }
}
