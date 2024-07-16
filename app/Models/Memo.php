<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Memo extends Model
{
    use HasFactory;

    /**
     * Attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'date',
        'content',
    ];

    /**
     * Attributes that should be cast to native types.
     *
     * @var array<int, string>
     */
    protected $casts = [
        'date' => 'datetime',
    ];

    /**
     * Memo belongs to Admin.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    /**
     * Perform any actions required after the model boots.
     */
    protected static function booted(): void
    {
        static::creating(function ($memo) {
            $memo->admin_id = auth()->id();
        });
    }

    /**
     * Scope a query to only include memos for the authenticated admin.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForAuthenticatedAdmin($query)
    {
        $adminId = auth()->id();

        return $query->where('admin_id', $adminId);
    }
}
