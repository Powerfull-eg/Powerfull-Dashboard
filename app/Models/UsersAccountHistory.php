<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsersAccountHistory extends Model
{
    use HasFactory;

    protected $table = 'users_account_history';

    protected $fillable = [
        'user_id',
        'action',
        'description',
        'notes',
        'done_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'done_by', 'id');
    }

}
