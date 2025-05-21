<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopsAdmin extends Model
{
    use HasFactory;

    protected $table = 'shops_admins';

    protected $fillable = [
        'shop_id',
        'admin_id',
    ];

    public function admin()
    {
        return $this->hasMany(Admin::class, 'id', 'admin_id');
    }

    public function shop()
    {
        return $this->hasMany(Shop::class, 'id', 'shop_id');
    }
}
