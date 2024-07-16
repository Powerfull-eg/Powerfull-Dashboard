<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\GiftUser;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Gift extends Model
{
        public $fillable = [
        "name",
        "title_ar",
        "title_en",
        "message_ar",
        "message_en",
        "image",
        "case",
    ];

    public function giftUser() : HasMany
    {
        return $this->hasMany(GiftUser::class);
    }
    
}