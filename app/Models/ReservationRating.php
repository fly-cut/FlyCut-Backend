<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReservationRating extends Model
{
    use HasFactory;

    protected $fillable = [
        'barbershop_id',
        'barber_id',
        'client_id',
        'reservation_id',
        'barber_rating',
        'barbershop_rating',
        'review',
        'image',
    ];

    public function barber()
    {
        return $this->belongsTo(Barber::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    public function barbershop()
    {
        return $this->belongsTo(Barbershop::class);
    }
}
