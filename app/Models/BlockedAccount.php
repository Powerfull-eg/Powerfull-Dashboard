<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlockedAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'blocked_by',
        'reason',
        'description',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
