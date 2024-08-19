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

    public $appends = [
        "location",
        "is_favourite",
    ];

    public $hidden = [ 'location_latitude', 'location_longitude' , 'created_at', 'updated_at'];

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
    public function getLocationAttribute()
    {
        return $this->location_latitude . ',' . $this->location_longitude;
    }

    /** 
     * Get full data.
     */
    public function data()
    {
        return $this->hasOne(ShopsData::class);
    }

    /**
     * Get the Menu of the Shop
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function menu()
    {
        return $this->HasMany(ShopsMenu::class);
    }

    /**
     * Get the Rates of the Shop
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rates()
    {
        return $this->HasMany(ShopsRate::class);
    }

    /**
     * Get the Reactions of the Shop
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reactions()
    {
        return $this->HasMany(ShopsReaction::class);
    }

    /**
     * Check if the Shop is favorited
     *
     */
    public function getIsFavouriteAttribute()
    {
        return auth('api')->user() ? ShopsFavourite::where('user_id',auth('api')->user()->id)->where('shop_id',$this->id)->exists() : null;
    }
}
