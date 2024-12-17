<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Operation extends Model
{
    use HasFactory,SoftDeletes;

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
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function operationTimeInSeconds()
    {
        return ((strtotime($this->returnTime) - strtotime($this->borrowTime)));
    }

    public function incompleteOperation(): HasOne
    {
        return $this->hasOne(IncompleteHistory::class);    
    }

    // public function borrowTime() : Attribute
    // {
    //     return Attribute::make(
    //         get: fn (string $value) => Carbon::parse($value)->subHours(6)->format('Y-m-d\TH:i:s'),
    //     );
    // }

    // public function returnTime(){
    //     if($this->returnTime == null) return null;
        
    //     return Attribute::make(
    //         get: fn (string $value) => Carbon::parse($value)->subHours(6)->format('Y-m-d\TH:i:s'),
    //     );
    // }
}