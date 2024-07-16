<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Merchant extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    public $fillable = [
        "name",
        "logo",
        "images",
        "governorate",
        "city",
        "address",
        "location",
        "created_by",
        "updated_by",
    ];

    public function stations() : HasMany
    {
        return $this->hasMany(Station::class);
    }
}
