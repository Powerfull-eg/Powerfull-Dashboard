<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ticket extends Model
{
    use HasFactory;

    public $fillable = [
        "user_id",
        "subject",
        "created_at",
        "updated_at"
    ];

    // Get ticket messages
    public function messages() : HasMany
    {
        return $this->hasMany(Message::class);
    }

    // Get user of the ticket
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function lastMessage(){
        return $this->messages()->latest();
    }
}
