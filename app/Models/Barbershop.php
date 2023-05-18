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
        'rating',
        'rating_count',
        'description',
        'longitude',
        'latitude',
        'address',
        'city',
    ];

    public function barbershopOwner()
    {
        return $this->belongsTo(BarbershopOwner::class);
    }

    public function barbers()
    {
        return $this->hasMany(Barber::class);
    }

    public function services()
    {
        return $this->belongsToMany(Service::class)->withPivot('slots', 'price');
    }

    public function ratings()
    {
        return $this->hasMany(BarbershopRating::class);
    }
}
