<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Refund extends Model
{
    use HasFactory;

    protected $fillable = [
        "operation_id",
        "amount",
        "reason",
    ];

    public function operation() {
        return $this->belongsTo(Operation::class);
    }
}
