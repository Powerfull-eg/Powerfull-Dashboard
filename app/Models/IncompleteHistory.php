<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IncompleteHistory extends Model
{
    use HasFactory;

    protected $table = 'incomplete_history';

    public $fillable = [
        "operation_id",
        "original_amount",
        "final_amount",
        "status",
        "ended_at"
    ];

    public function operation() : BelongsTo
    {
        return $this->belongsTo(Operation::class);
    }
}
