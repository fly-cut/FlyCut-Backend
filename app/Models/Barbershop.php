<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barbershop extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image',
        'rating'
    ];

    public function barbershopOwner()
    {
        return $this->belongsTo(BarbershopOwner::class);
    }

    public function barbers()
    {
        return $this->hasMany(Barber::class);
    }

    public function barbershopAddresses()
    {
        return $this->hasMany(BarbershopAddress::class);
    }
}
