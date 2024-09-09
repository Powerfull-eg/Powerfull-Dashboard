<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopsType extends Model
{
    use HasFactory;

    protected $fillable = [
        'shop_id',
        'type_ar_name',
        'type_en_name',
        'type_icon',
        'access_ar_name',
        'access_en_name',
        'access_icon',
    ];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function typeIcon(): Attribute
    {
        return Attribute::make(
            get: fn ($icon) => ($icon ? asset('storage/types/' . $icon) : null),
        );
    }

    public function accessIcon(): Attribute
    {
        return Attribute::make(
            get: fn ($icon) => ($icon ? asset('storage/types/' . $icon) : null),
        );
    }
}