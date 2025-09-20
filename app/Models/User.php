<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'balance'
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'user_id', 'id');
    }
}


