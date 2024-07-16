<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Operation extends Model
{
    use HasFactory;

    public $fillable = [
        "station_id",
        "powerbank_id",
        "user_id",
        "card_id",
        "tradeNo",
        "borrowTime",
        "returnTime",
        "borrowSlot",
        "payment_id",
        "type",
        "status",
        "amount"
    ];


    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class,"station_id","device_id");
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function operationTimeInSeconds()
    {
        return ((strtotime($this->returnTime) - strtotime($this->borrowTime)));
    }
}