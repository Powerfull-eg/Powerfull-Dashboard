<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Station extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $fillable = [
        "inet_id",
        "status",
        "signal_value",
        "type",
        "slots",
        "merchant_id",
        "rentable_slots",
        "return_slots",
        "fault_slots",
        "internet_card",
        "device_ip",
        "server_ip",
        "port",
        "authorize",
        "created_by",
        "updated_by",
    ];

    public function scopegetStations($query): void
    {
        $query->all();
    }
    
    public function merchant() : BelongsTo
    {
        return $this->belongsTo(Merchant::class,"merchant_id","id");
    }
    
    public function heartbeat() : HasOne
    {
        return $this->hasOne(Heartbeat::class,"station_id","id");
    }


}

