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
        return $this->belongsToMany(Admin::class, 'admins', 'id', 'admin_id');
    }

    public function shop()
    {
        return $this->belongsToMany(Shop::class, 'shops', 'id', 'shop_id');
    }
}
