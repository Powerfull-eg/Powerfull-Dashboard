<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Voucher extends Model
{
    use HasFactory;
    public $fillable = [
        "code",
        "user_id",
        "type",
        "value",
        "min_amount",
        "max_discount",
        "from",
        "to",
        "image",
        "multiple_usage",
        "usage_count",
        "campaign_id"
    ];

    public function voucherOrder() : HasMany
    {
        return $this->hasMany(VoucherOrder::class);
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function campaign() : BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }
}