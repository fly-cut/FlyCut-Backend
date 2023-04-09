<?php

namespace App\Models;


// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barber extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
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