<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Campaign extends Model
{
    use HasFactory;
    
    public $guarded = [];

    public function vouchers(): HasMany
    {
        return $this->hasMany(Voucher::class);
    }

}
