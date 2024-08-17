<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopsData extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }
    
    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function getLoacationAttribute()
    {
        return $this->lat . ',' . $this->lng;
    }

}
