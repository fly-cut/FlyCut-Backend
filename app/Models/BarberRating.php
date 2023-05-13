<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarberRating extends Model
{
    use HasFactory;

    protected $fillable = [
        'barber_id',
        'client_id',
        'rating',
        'review',
    ];

    public function barber()
    {
        return $this->belongsTo(Barber::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
