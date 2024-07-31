<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Gift;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GiftUser extends Model
{
    use HasFactory;
    public $fillable = [
        "gift_id",
        "user_id",
        "code",
        "expire",
        "used_at",
        "shop_id",
        "shop_name",
    ];

    public function gift() : BelongsTo
    {
        return $this->belongsTo(Gift::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function shop() : BelongsTo
    {
        return $this->belongsTo(Shop::class,"provider_id","shop_id");
    }

}
