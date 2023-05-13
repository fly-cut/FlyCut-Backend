<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class Client extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $guard = 'client';

    protected $fillable = [
        'name',
        'email',
        'password',
        'image',
        'birth_date',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function client_providers()
    {
        return $this->hasMany(ClientProvider::class, 'client_id', 'id');
    }
}
