<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class BarbershopOwner extends Authenticatable
{
    use HasFactory, Notifiable,  HasApiTokens;

    protected $guard = 'barbershopOwner-api';

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'birth_date',
        'image',
        'device_token',
        'has_barbershop',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function barbershop()
    {
        return $this->hasMany(Barbershop::class);
    }

    public function providers()
    {
        return $this->hasMany(Provider::class, 'barbershop_owner_id', 'id');
    }
}
