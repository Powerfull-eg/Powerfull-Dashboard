<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
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

    /**
     * Get all operations belongs to this shop.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    
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

    public function logo() : Attribute
    {
        return Attribute::make(
            get: fn ($value) => filter_var($value, FILTER_VALIDATE_URL) !== false ? $value 
            : url('storage/shops/' . $this->id . '/' . $value),
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


    // get saved users
    public function savedUsers() : HasMany
    {
        return $this->hasMany(ShopsSave::class);
    }

    // Notes Relations
    public function notes() : HasMany
    {
        return $this->hasMany(Note::class, 'type_id', 'id')->where('type', 'shops');
    }
    
    /**
     * Check if the Shop is saved
     *
    */
    // public function getIsSavedAttribute()
    // {
    //     return auth('api')->user() ? ShopsSave::where('user_id',auth('api')->user()->id)->where('shop_id',$this->id)->exists() : false;
    // }

}
