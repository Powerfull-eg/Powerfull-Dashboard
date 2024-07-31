<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Card extends Model
{
    use HasFactory;
    
    public $fillable = [
        "user_id",
        "card_number",
        "card_type",
        "identifier_token",
        "paymob_response",
    ];
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

}