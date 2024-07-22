<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shop extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    public $fillable = [
        "name",
        "phone",
        "provider_id",
        "icon",
        "logo",
        "images",
        "governorate",
        "city",
        "address",
        "location_latitude",
        "location_longitude",
        "created_by",
        "updated_by",
    ];

    public function device() : HasOne
    {
        return $this->hasOne(Device::class);
    }

    public function gifts() : HasMany
    {
        return $this->hasMany(GiftUser::class);
    }

    public function operations() : HasManyThrough 
    {
        return $this->hasManyThrough(
            Operation::class, 
            Device::class,
            "shop_id",
            "station_id",
            "id",
            "id"

        );
    }

    /** 
     * Get full Location.
     */
    public function getFullLocation()
    {
        return $this->location_latitude . ',' . $this->location_longitude;
    }
}
