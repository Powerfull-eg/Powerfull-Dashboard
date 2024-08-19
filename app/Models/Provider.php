<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Provider extends Model
{
    use HasFactory , SoftDeletes;

    protected $fillable = [
        'name',
        'icon',
        'logo',
        'link',
        'controller',
    ];

    public $hidden = [ 'created_at', 'updated_at', 'deleted_at'];

    public function devices()
    {
        return $this->hasMany(Device::class);
    }
}
