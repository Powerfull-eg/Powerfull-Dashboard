<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;
    public $fillable = [
        "code",
        "user_id",
        "type",
        "percentage",
        "amount",
        "min_amount",
        "max_discount",
        "used_at",
        "starts_at",
        "expires_at",
        "image"
    ];
}