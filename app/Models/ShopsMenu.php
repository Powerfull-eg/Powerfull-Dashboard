<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class ShopsMenu extends Model
{
    use HasFactory;

    protected $fillable = ['image', 'is_hero','shop_id'];

    public $hidden = [ 'created_at', 'updated_at' , 'shop_id' ];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function image() : Attribute
    {
        return Attribute::make(
            get: fn ($image) => ($image ? asset('storage/shops/' . $this->shop_id . '/menu/' . $image) : null),
        );
    }
}
