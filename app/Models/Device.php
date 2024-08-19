<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Device extends Model
{
    use HasFactory;


    public $fillable = [
        "device_id",
        "shop_id",
        "status",
        "slots",
        "created_by",
        "updated_by",
        "provider_id"
    ];

    public $hidden = [ 'provider_id', 'created_at', 'updated_at' ,'created_by', 'updated_by', 'deleted_at','shop_id' ];

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function operations(): HasMany
    {
        return $this->hasMany(Operation::class,"station_id","device_id");
    }
}