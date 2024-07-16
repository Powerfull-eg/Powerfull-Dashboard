<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    public $fillable = [
        "user_id",
        "response_data",
        "card_details",
        "payment_order_id",
        "token",
        "type"
    ];
}