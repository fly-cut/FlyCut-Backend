<?php

namespace App\Models;


// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Barber extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'rating',
        'birth_date',
        'image',
        'barbershop_id'
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function barbershop()
    {
        return $this->belongsTo(Barbershop::class);
    }
}