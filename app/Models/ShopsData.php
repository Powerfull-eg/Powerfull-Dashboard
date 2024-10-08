<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShopsData extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $appends = [ 'location' , 'type'];

    public $hidden = [ 'lat', 'lng' , 'created_at', 'updated_at' , 'admin_id' , 'shop_id','type_id' ];
    
    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }
    
    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function getLocationAttribute()
    {
        return $this->lat && $this->lng ? $this->lat . ',' . $this->lng : null;
    }

    public function logo() : Attribute
    {
        return Attribute::make(
            get: fn ($logo) => (
                $logo 
                ? (filter_var($logo, FILTER_VALIDATE_URL) !== false ? $logo : url('storage/shops/' . $this->shop_id . '/' . $logo)) 
                : null)
        );
    }

    public function getTypeAttribute()
    {
        return ShopsType::find($this->type_id);
    }

}