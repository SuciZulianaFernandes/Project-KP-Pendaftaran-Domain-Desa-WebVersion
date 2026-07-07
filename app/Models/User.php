<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Concerns\HasUuidRouteKey;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasUuidRouteKey;

    protected $table = 'users';

    protected $primaryKey = 'id_user';

    protected $fillable = [
        'username',
        'name',
        'email',
        'no_hp',
        'password',
        'role'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function desa()
    {
        return $this->hasOne(Desa::class, 'id_user', 'id_user');
    }
}