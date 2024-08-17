<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopsMenu extends Model
{
    use HasFactory;

    protected $fillable = ['image', 'is_hero','shop_id'];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }
}
