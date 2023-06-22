<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;
    public function services()
    {
        return $this->belongsToMany(ReservationVariation::class);
    }
    protected $fillable = ['barber_id', 'user_id', 'barbershop_id', 'price'];
}
