<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarbershopRating extends Model
{
    use HasFactory;

    protected $fillable = [
        'barbershop_id',
        'client_id',
        'reservation_id',
        'rating',
        'review',
        'image',
    ];

    public function barbershop()
    {
        return $this->belongsTo(Barbershop::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }
}
