<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopsRate extends Model
{
    use HasFactory;

    protected $fillable = ['rate', 'comment', 'hidden', 'user_id', 'shop_id'];

    public $appends = [ 'user_name' ];

    public $hidden = [ 'created_at', 'updated_at' , 'shop_id'];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeHidden($query)
    {
        return $query->where('hidden', 'yes');
    }

    public function getUserNameAttribute()
    {
        return User::where('id', $this->user_id)->first()->fullName;
    }
}
