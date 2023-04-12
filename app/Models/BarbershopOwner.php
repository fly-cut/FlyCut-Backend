<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class BarbershopOwner extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;


    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'birth_date',
        'image'
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function barbershop()
    {
        return $this->hasOne(Barbershop::class);
    }
    public function providers()
    {
        return $this->hasMany(Provider::class, 'barbershop_owner_id', 'id');
    }
}
