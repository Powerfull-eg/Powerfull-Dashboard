<?php

namespace App\Models;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class Admin extends Authenticatable
{
    use HasApiTokens, HasFactory, HasRoles, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_picture',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Perform any actions required after the model boots.
     */
    protected static function booted(): void
    {
        ResetPassword::createUrlUsing(function (Admin $admin, string $token) {
            return url(route('dashboard.password.reset', [
                'token' => $token,
                'email' => $admin->email,
            ], false));
        });
    }

    /**
     * Scope a query to only include admins that are not the current admin.
     */
    public function scopeWhereNotCurrentAdmin($query): void
    {
        $query->where('id', '!=', auth()->id());
    }
}
