<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'image'];

    public function barbershops()
    {
        return $this->belongsToMany(Barbershop::class);
    }

    public function variations()
    {
        return $this->hasMany(Variation::class);
    }

    public function reservations()
    {
        return $this->belongsToMany(Reservation::class);
    }
}
